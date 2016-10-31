<?php

require_once (__DIR__ . '/../vendor/autoload.php');


function SelenideClassLoader($className)
{
    $path = str_replace(array('\\', '_'), '/', $className);
    $filename = __DIR__ . '/' . $path . '.php';
    if (file_exists($filename)) {
        require_once ($filename);
    }
}

spl_autoload_register('SelenideClassLoader');