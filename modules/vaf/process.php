<?php
error_reporting(E_ALL & ~E_NOTICE);
define('_PS_VERSION_',true);
define( 'ELITE_CONFIG_DEFAULT', dirname(__FILE__).'/config.default.ini' );
define( 'ELITE_CONFIG', dirname(__FILE__).'/config.ini' );

require_once('autoload.php');
require_once('HelperData.php');
require_once('Vehicle-Fits-Core/library/VF/html/vafAjax.include.php');