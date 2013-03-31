<?php
set_include_path( 
    dirname(__FILE__) . '/lib'
    . PATH_SEPARATOR . get_include_path()
);

spl_autoload_register('VafLoad');

function VafLoad($class)
{
    $file = str_replace('_','/',$class).'.php';
    require_once($file);
}
        