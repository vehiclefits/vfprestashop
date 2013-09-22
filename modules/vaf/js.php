<?php
header('Content-Type:application/x-javascript');
error_reporting(E_ALL & ~E_NOTICE);
define('_PS_VERSION_',true);
define( 'ELITE_CONFIG_DEFAULT', dirname(__FILE__).'/config.default.ini' );
define( 'ELITE_CONFIG', dirname(__FILE__).'/config.ini' );

require_once 'bootstrap.php';

require_once('vendor/vehiclefits/vehicle-fits-core/library/VF/html/vafAjax.js.include.php');