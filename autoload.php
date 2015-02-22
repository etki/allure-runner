<?php

use Etki\Testing\AllureFramework\Runner\Utility\Autoloader;

require_once __DIR__ . '/src/Utility/Autoloader.php';

$autoloader = new Autoloader;
$autoloader->registerNamespace(
    '\Etki\Testing\AllureFramework\Runner',
    __DIR__ . '/src/'
);

spl_autoload_register(array($autoloader, 'loadClass',));
