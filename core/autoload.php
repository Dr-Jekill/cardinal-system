<?php

define("APPLICATION_PATH", dirname(dirname(__FILE__)));
define("DS", DIRECTORY_SEPARATOR);
spl_autoload_register(function($class){
    try {
        require_once realpath(APPLICATION_PATH . DS . str_replace("\\", DS, $class) . ".php");        
    }  
    catch (Exception $ex){
        echo $ex->getMessage();
    }
});