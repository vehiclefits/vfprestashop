<?php
require_once('vendor/autoload.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'bootstrap.php';

class Vaf extends Module
{
    function __construct()
    {
        $this->name = 'vaf';
        $this->tab = 'vaf';
        $this->version = 1.0;
        $this->author = 'Josh Ribakoff';
        $this->need_instance = 0;

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
        return $this->registerHook('leftColumn') && $this->registerHook('displayHome');
    }

    function hookDisplayAdminProductsExtra($obj)
    {
        ob_start();
        include('vafadmin.phtml');
        $content = ob_get_clean();
        return $content;
    }

    public function hookDisplayHome($params)
    {
        return $this->hookLeftColumn($params);
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

}