<?php
class vafvfresultsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        // get link to this controller from the vaf.php module class with this code:
        // $this->context->link->getModuleLink('vaf', 'test')
        parent::initContent();

        $db = VF_Singleton::getInstance()->getReadAdapter();
        $subQuery = 'select DISTINCT(id_category) FROM ps_category_product WHERE id_product IN ('.implode(',',$this->vafProductIds()).')';
        $query = 'select * from ps_category_lang where id_category IN (' . $subQuery . ')';
        $result = $db->query($query);

        $categories = array();
        while($row = $result->fetch()) {
            array_push($categories, array(
                'id' => $row['id_category'],
                'name' => $row['name'],
                'description' => $row['description'],
            ));
        }

        ob_start();
        include('results.phtml');
        $html = ob_get_clean();

        $this->prestashopRender($html);
    }

    function prestashopRender($html)
    {
        $this->context->smarty->assign('html', $html);
        $this->setTemplate('vfresults.tpl');
    }

    function vafProductIds()
    {
        $this->flexibleSearch()->storeFitInSession();
        $productIds = $this->flexibleSearch()->doGetProductIds();
        return $productIds;
    }

    /** @return VF_FlexibleSearch */
    function flexibleSearch()
    {
        $search = new VF_FlexibleSearch(new VF_Schema, new Zend_Controller_Request_Http);
        $search->setConfig($this->vafConfig());
        return $search;
    }

    function vafConfig()
    {
        return new Zend_Config(array());
    }
}