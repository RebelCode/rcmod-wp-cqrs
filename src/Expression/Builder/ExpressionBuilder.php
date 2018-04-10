<?php

namespace RebelCode\Expression\Builder;

use ArrayAccess;
use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Container\NormalizeContainerCapableTrait;
use Dhii\Data\Container\NormalizeKeyCapableTrait;
use Dhii\Data\Object\DataStoreAwareContainerTrait;
use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\Expression\TermInterface;
use Dhii\Factory\Exception\CouldNotMakeExceptionInterface;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

/**
 * A generic expression builder.
 *
 * This builder will map the name of any public method called for it to an expression factory. The factory should take
 * care to invoke the arguments given to the called builder method, which can be retrieved from the config given to it
 * from index "arguments".
 *
 * @since [*next-version*]
 */
class ExpressionBuilder
{
    /*
     * Provides awareness of a container data store.
     *
     * @since [*next-version*]
     */
    use DataStoreAwareContainerTrait;

    /*
     * Provides functionality for reading data from any type of container.
     *
     * @since [*next-version*]
     */
    use ContainerGetCapableTrait;

    /*
     * Provides key normalization functionality.
     *
     * @since [*next-version*]
     */
    use NormalizeKeyCapableTrait;

    /*
     * Provides string normalization functionality.
     *
     * @since [*next-version*]
     */
    use NormalizeStringCapableTrait;

    /*
     * Providers container normalization functionality.
     *
     * @since [*next-version*]
     */
    use NormalizeContainerCapableTrait;

    /*
     * Provides functionality for creating container exceptions.
     *
     * @since [*next-version*]
     */
    use CreateContainerExceptionCapableTrait;

    /*
     * Provides functionality for creating not-found exceptions.
     *
     * @since [*next-version*]
     */
    use CreateNotFoundExceptionCapableTrait;

    /*
     * Provides functionality for creating invalid-argument exceptions.
     *
     * @since [*next-version*]
     */
    use CreateInvalidArgumentExceptionCapableTrait;

    /*
     * Provides functionality for creating out-of-range exceptions.
     *
     * @since [*next-version*]
     */
    use CreateOutOfRangeExceptionCapableTrait;

    /*
     * Provides string translating functionality.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param array|ArrayAccess|stdClass|ContainerInterface $factories The expression factories.
     */
    public function __construct($factories)
    {
        $this->_setDataStore($factories);
    }

    /**
     * Magic invocation builder method: maps the called method name to a factory and creates an expression instance.
     *
     * @since [*next-version*]
     *
     * @param string $name      The name of the called method.
     * @param array  $arguments The arguments given to the called method.
     *
     * @throws NotFoundExceptionInterface If the called method could not be mapped to an expression factory.
     * @throws ContainerExceptionInterface If an error occurred while mapping to an expression factory.
     * @throws CouldNotMakeExceptionInterface If an error occurred while creating the expression instance.
     *
     * @return TermInterface The created expression.
     */
    public function __call($name, $arguments)
    {
        $key = $this->_getExpressionFactoryKey($name);
        $config = $this->_getExpressionFactoryConfig($arguments);

        return $this->_containerGet($this->_getDataStore(), $key)->make($config);
    }

    /**
     * Retrieves the expression factory key that corresponds the name of the called builder method.
     *
     * @since [*next-version*]
     *
     * @param string $methodName The name of the called method.
     *
     * @return string|Stringable The key that maps to the expression factory.
     */
    protected function _getExpressionFactoryKey($methodName)
    {
        return $methodName;
    }

    /**
     * Retrieves the expression factory config that corresponds to the given set of builder method arguments.
     *
     * @since [*next-version*]
     *
     * @param array $arguments The arguments given to the builder method.
     *
     * @return array|stdClass|ArrayAccess|ContainerInterface The expression factory config.
     */
    protected function _getExpressionFactoryConfig($arguments)
    {
        return [
            'arguments' => $arguments,
        ];
    }
}
