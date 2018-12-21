<?php

use RebelCode\Storage\Resource\WordPress\Module\Module;

$key = 'wp_cqrs';
$deps = [];
$configFile = __DIR__ . '/config.php';
$servicesFile = __DIR__ . '/services.php';

return new Module($key, $deps, $configFile, $servicesFile);
