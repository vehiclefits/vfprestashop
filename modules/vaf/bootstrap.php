<?php
define( 'ELITE_CONFIG_DEFAULT', dirname(__FILE__).'/config.default.ini' );
define( 'ELITE_CONFIG', dirname(__FILE__).'/config.ini' );
define( 'ELITE_PATH', '.' );
require_once('vendor/autoload.php');

VF_Singleton::getInstance()->setProcessURL('/modules/vaf/process.php?');
$database = new VF_TestDbAdapter(array(
    'dbname' => 'prestashop',
    'username' => 'root',
    'password' => ''
));
VF_Singleton::getInstance()->setReadAdapter($database);