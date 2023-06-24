<?php

spl_autoload_register(function ($className) {
    if (strpos($className, 'Model\\') === 0) {
        $className = str_replace('Model\\', '', $className);
     
        $filePath = str_replace('\\', '/', $className) . '.php';
     
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
});
