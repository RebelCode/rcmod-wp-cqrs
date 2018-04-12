<?php

namespace RebelCode\Expression;

use Dhii\Expression\Renderer\AbstractBaseSelfDelegateExpressionTemplate;
use Psr\Container\ContainerInterface;

/**
 * A self-delegating master expression template.
 *
 * Given any template it delegates rendering to another template, retrieved by expression type from a container.
 *
 * @since [*next-version*]
 */
class SqlExpressionMasterTemplate extends AbstractBaseSelfDelegateExpressionTemplate
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface $container The delegate container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_setTermTypeRendererContainer($container);
    }
}
