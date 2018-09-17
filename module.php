<?php

use Psr\Container\ContainerInterface;
use RebelCode\Storage\Resource\WordPress\Module\WpCqrsModule;

return function (ContainerInterface $c) {
    return new WpCqrsModule(
        [
            'key'                => 'wp_cqrs',
            'dependencies'       => [],
            'root_dir'           => __DIR__,
            'config_file_path'   => __DIR__ . '/config.php',
            'services_file_path' => __DIR__ . '/services.php',
        ],
        $c->get('config_factory'),
        $c->get('container_factory'),
        $c->get('composite_container_factory')
    );
};
