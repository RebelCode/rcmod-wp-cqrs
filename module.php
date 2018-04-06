<?php

use Psr\Container\ContainerInterface;
use RebelCode\Storage\Resource\WordPress\Module\WpCqrsModule;

return function(ContainerInterface $c) {
    return new WpCqrsModule('wp_cqrs', $c->get('container_factory'));
};
