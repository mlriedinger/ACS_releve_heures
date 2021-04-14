<?php

spl_autoload_register(function($class){
    if(file_exists('controller/' . $class . '.php')) {
        require_once 'controller/' . $class . '.php';
    }
    if(file_exists('model/' . $class . '.php')) {
        require_once 'model/' . $class . '.php';
    }
    if(file_exists('exception/' . $class . '.php')) {
        require_once 'exception/' . $class . '.php';
    }
});