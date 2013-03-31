<?php
class Elite_Vaf_Helper_Data
{
    static $dbAdapter;
    
    /** @return Elite_Vaf_Helper_Data */
    static function getInstance($new=false) // test only
    {
	static $instance;
	if (is_null($instance) || $new)
	{
	    $instance = new Elite_Vaf_Helper_Data;
	}
	return $instance;
    }
    
    static function getBaseUrl()
    {
        return '';
    }
    
    function getConfig()
    {
        return new Zend_Config_Ini(dirname(__FILE__).'/config.ini');
    }
    
    function showSearchButton()
    {
        return true;
    }
    
    function getLoadingText()
    {
        return 'loading';
    }
    
    function addFitment($productId, $fit)
    {
        $finder = new VF_Vehicle_Finder(new VF_Schema);
        $vehicles = $finder->findByLevelIds($fit);
	$mapping_id = null;
	foreach ($vehicles as $vehicle)
	{
            $mapping = new VF_Mapping($productId, $vehicle);
            return $mapping->save();
	}
        
    }
    
    function deleteFitment($productId, $mapping_id)
    {
        $sql = sprintf("DELETE FROM `elite_1_mapping` WHERE `id` = %d", (int) $mapping_id);
	$this->query($sql);
    }
    
    function getFits($productId)
    {
	$select = new VF_Select($this->getReadAdapter());
	$select->from('elite_1_mapping')
		->addLevelTitles()
		->where('entity_id=?', $productId);
	$result = $this->query($select);

	$fits = array();
	while ($row = $result->fetchObject())
	{
	    if ($row->universal)
	    {
		continue;
	    }
	    $fits[] = $row;
	}
	return $fits;
    }
    
    /** @return Zend_Db_Statement_Interface */
    protected function query($sql)
    {
	return $this->getReadAdapter()->query($sql);
    }

    function getReadAdapter()
    {
	if(isset(self::$dbAdapter)) return self::$dbAdapter;
	
	
        if (is_null(self::$dbAdapter))
        {

            self::$dbAdapter = new Zend_Db_Adapter_Pdo_Mysql(array('dbname' => 'prestashop', 'username' => 'root', 'password' => ''));
            self::$dbAdapter->getConnection()->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

            self::$dbAdapter->getConnection()->query('SET character set utf8;');
            self::$dbAdapter->getConnection()->query('SET character_set_client = utf8;');
            self::$dbAdapter->getConnection()->query('SET character_set_results = utf8;');
            self::$dbAdapter->getConnection()->query('SET character_set_connection = utf8;');
            self::$dbAdapter->getConnection()->query('SET character_set_database = utf8;');
            self::$dbAdapter->getConnection()->query('SET character_set_server = utf8;');
        }
        return self::$dbAdapter;
    }
    
    function getRequest()
    {
        return new Zend_Controller_Request_Http;        
    }

    function showLabels()
    {
        return true;
    }
    
    function getDefaultSearchOptionText()
    {
        return '-please select-';
    }
    
    function displayBrTag()
    {
        return true;
    }
    
    function vehicleSelection()
    {
	$search = new VF_FlexibleSearch(new VF_Schema, new Zend_Controller_Request_Http);
	return $search->vehicleSelection();
    }
    
    function processUrl()
    {
        return $this->getBaseUrl(isset($_SERVER['HTTPS']) ) . '/modules/vaf/process.php?';
    }
    
    function homepageSearchURL()
    {
        return '?';
    }
}