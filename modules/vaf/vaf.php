<?php
require_once('autoload.php');
require_once('HelperData.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

define( 'ELITE_CONFIG_DEFAULT', dirname(__FILE__).'/config.default.ini' );
define( 'ELITE_CONFIG', dirname(__FILE__).'/config.ini' );

class Vaf extends Module
{
    function __construct()
    {
        $this->name = 'vaf';
        $this->tab = 'Vehicle Fits';
        $this->version = 1.0;
        $this->author = 'Josh Ribakoff';
        $this->need_instance = 0;

        session_start();

        parent::__construct();

        $this->displayName = $this->l('Vehicle Fits');
        $this->description = $this->l('Year/Make/Model filter');
    }

    function install()
    {
        if (parent::install() == false || !$this->registerHooks()) {
            return false;
        }
        return true;
    }

    function registerHooks()
    {
        return $this->registerHook('leftColumn') &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('actionProductSave') &&
            $this->registerHook('productListAssign');
    }

    function hookDisplayAdminProductsExtra($obj)
    {
        ob_start();
        include('vafadmin.phtml');
        $content = ob_get_clean();
        return $content;
    }

    function hookLeftColumn($params)
    {
        $search = new VF_FlexibleSearch(new VF_Schema, new Zend_Controller_Request_Http);
        $search->setConfig(new Zend_Config(array()));
        $search->storeFitInSession();

        ob_start();
        include('search.phtml');
        $content = ob_get_clean();
        return $content;
    }

    function hookRightColumn($params)
    {
        return $this->hookLeftColumn($params);
    }

    function hookActionProductSave()
    {
        $this->removeFitments();
        $this->addNewFitments();
    }

    function hookProductListAssign($params)
    {
        if ($params['nbProducts']) {
            return;
        }
        echo 'There is a conflicting module with Vehicle Fit, please contact support for instructions on fixing.';
        // exit();
    }

    function vafProductIds()
    {
        //return '1';
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

    function removeFitments()
    {
        $request = new Zend_Controller_Request_Http;
        $schema = new VF_Schema();
        if (is_array($request->getParam('vaf-delete')) && count($request->getParam('vaf-delete')) >= 1) {
            foreach ($request->getParam('vaf-delete', array()) as $fit) {
                $fit = explode('-', $fit);
                $level = $fit[0];
                $fit = $fit[1];
                if ($level == $schema->getLeafLevel()) {
                    Elite_Vaf_Helper_Data::getInstance()->deleteFitment($_GET['id_product'], $fit);
                }
            }
        }
    }


    function addNewFitments()
    {
        $request = new Zend_Controller_Request_Http;
        if (is_array($request->getParam('vaf')) && count($request->getParam('vaf')) >= 1) {
            foreach ($request->getParam('vaf') as $fit) {
                if (strpos($fit, ':') && strpos($fit, ';')) {
                    // new logic
                    $params = explode(';', $fit);
                    $newParams = array();
                    foreach ($params as $key => $value) {
                        $data = explode(':', $value);
                        if (count($data) <= 1) {
                            continue;
                        }

                        $newParams[$data[0]] = $data[1];
                    }
                    Elite_Vaf_Helper_Data::getInstance()->addFitment($_GET['id_product'], $newParams);
                }
            }
        }
    }

}