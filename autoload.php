<?php

spl_autoload_register(function ($className) {
    $namespaceMap = [
        'Model\\' => 'App/models/',
        'Controller\\' => 'App/controllers/',
        'Service\\' => 'App/services/',
        'Config\\' => 'App/config/'
    ];

    foreach ($namespaceMap as $namespacePrefix => $directory) {
        if (strpos($className, $namespacePrefix) === 0) {
            $className = str_replace($namespacePrefix, '', $className);
            $filePath = $directory . str_replace('\\', '/', $className) . '.php';

            if (file_exists($filePath)) {
                require_once $filePath;
                return;
            }
        }
    }
});
