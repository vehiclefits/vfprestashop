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

        if(!isset($_SESSION)) {
            session_start();
        }
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
        return $this->registerHook('leftColumn') && $this->registerHook('displayHome') && $this->registerHook('displayHeader');
    }

    function hookDisplayHeader()
    {
        /**
         * This hook is not actually used.
         * It is a hack of PrestaShops architecture to force my module to load on every page.
         * This hook is called on every page, which is why it was choosen to be added here.
         *
         * The reason my module should load on every page, is the Category override is not correlated to my module.
         * When my module adds an override for the Category class, Prestashop overrides that class, but it does not
         * make the correlation that the overriden category class may contain code that depends on my module having loaded first.
         *
         * If my module is used on the "left" column, and that column is also present on the category page; this all works fine.
         *
         * It is when the user wants to hook my module into a section of the homepage, but still have it filter on the category page.
         *
         * As you can see, Prestashops architecture doesn't account for this situation, where my search form is not displayed on the page
         * that is required to process the results (category page). Due to this, we must use some sort of hack to force my code
         * to run on the category page - the most obvious way was to hook into some section that is on every page, such as this section - the header.
         */
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