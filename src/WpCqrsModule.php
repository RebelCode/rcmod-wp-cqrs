<?php

namespace RebelCode\Storage\Resource\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlLogicalTypeInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlOperatorInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlRelationalTypeInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use RebelCode\Expression\Renderer\Sql\SqlBetweenExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlFunctionExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlOperatorExpressionTemplate;
use RebelCode\Modular\Module\AbstractBaseModule;

/**
 * WordPress CQRS module class.
 *
 * @since [*next-version*]
 */
class WpCqrsModule extends AbstractBaseModule
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable              $key              The module key.
     * @param ContainerFactoryInterface|null $containerFactory The container factory, if any.
     */
    public function __construct($key, $containerFactory)
    {
        $this->_initModule($containerFactory, $key);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function setup()
    {
        return $this->_createContainer(
            [
                // Container with all SQL expression templates
                'sql-expression-template-container' => function(ContainerInterface $c) {
                    return $c->get('container_factory')->make(
                        [
                            SqlLogicalTypeInterface::T_AND                 => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_AND, $c);
                            },
                            SqlLogicalTypeInterface::T_OR                  => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_OR, $c);
                            },
                            SqlLogicalTypeInterface::T_NOT                 => function(ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(SqlOperatorInterface::OP_NOT, $c);
                            },
                            SqlRelationalTypeInterface::T_EQUAL_TO         => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_GREATER_THAN     => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_GREATER_THAN, $c);
                            },
                            SqlRelationalTypeInterface::T_GREATER_EQUAL_TO => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_GREATER_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_LESS_THAN        => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_SMALLER, $c);
                            },
                            SqlRelationalTypeInterface::T_LESS_EQUAL_TO    => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_SMALLER_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_LIKE             => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_LIKE, $c);
                            },
                            SqlRelationalTypeInterface::T_IN               => function(ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_IN, $c);
                            },
                            SqlRelationalTypeInterface::T_BETWEEN          => function(ContainerInterface $c) {
                                return new SqlBetweenExpressionTemplate($c);
                            },
                            SqlRelationalTypeInterface::T_EXISTS           => function(ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(SqlOperatorInterface::OP_EXISTS, $c);
                            },
                        ]
                    );
                },
                // Master SQL expression template
                'sql-expression-template'           => function(ContainerInterface $c) {
                    return new SqlExpressionMasterTemplate($c->get('sql-expression-template-container'));
                },
                // WordPress' database adapter/connection
                'wpdb'                              => function(ContainerInterface $c) {
                    global $wpdb;

                    return $wpdb;
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null)
    {
    }
}
