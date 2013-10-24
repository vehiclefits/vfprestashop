<?php
error_reporting(E_ALL & ~E_NOTICE);
define('_PS_VERSION_',true);
define( 'ELITE_CONFIG_DEFAULT', dirname(__FILE__).'/config.default.ini' );
define( 'ELITE_CONFIG', dirname(__FILE__).'/config.ini' );

require_once 'bootstrap.php';
require_once('vendor/vehiclefits/library/library/VF/html/vafAjax.include.php');
