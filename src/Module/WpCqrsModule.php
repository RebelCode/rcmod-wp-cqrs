<?php

namespace RebelCode\Storage\Resource\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Factory\GenericCallbackFactory;
use Dhii\Storage\Resource\Sql\Expression\SqlLogicalTypeInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlOperatorInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlRelationalTypeInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use RebelCode\Expression\Builder\ExpressionBuilder;
use RebelCode\Expression\EntityFieldTerm;
use RebelCode\Expression\LiteralTerm;
use RebelCode\Expression\LogicalExpression;
use RebelCode\Expression\Renderer\Sql\SqlBetweenExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlFunctionExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlLiteralTermTemplate;
use RebelCode\Expression\Renderer\Sql\SqlOperatorExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlReferenceTermTemplate;
use RebelCode\Expression\VariableTerm;
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
                // WordPress' database adapter/connection
                'wpdb' => function (ContainerInterface $c) {
                    global $wpdb;

                    return $wpdb;
                },
                'wpdb_table_prefixer' => function (ContainerInterface $c) {
                    $wpdb = $c->get('wpdb');

                    return function ($table) use ($wpdb) {
                        return $wpdb->prefix . $table;
                    };
                },
                'wpdb_table_map_prefixer' => function (ContainerInterface $c) {
                    $prefixer = $c->get('wpdb_table_prefixer');

                    return function ($tables) use ($prefixer) {
                        $prefixed = [];

                        foreach ($tables as $_table => $_alias) {
                            $prefixed[$prefixer($_table)] = $_alias;
                        }

                        return $prefixed;
                    };
                },
                // Container with all SQL expression templates
                'sql_expression_template_container' => function (ContainerInterface $c) {
                    return $c->get('container_factory')->make([
                        'definitions' => [
                            'literal' => function (ContainerInterface $c) {
                                return new SqlLiteralTermTemplate();
                            },
                            'variable' => function (ContainerInterface $c) {
                                return new SqlReferenceTermTemplate();
                            },
                            'entity_field' => function (ContainerInterface $c) {
                                return new SqlReferenceTermTemplate();
                            },
                            SqlLogicalTypeInterface::T_AND => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_AND, $c);
                            },
                            SqlLogicalTypeInterface::T_OR => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_OR, $c);
                            },
                            SqlLogicalTypeInterface::T_NOT => function (ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(SqlOperatorInterface::OP_NOT, $c);
                            },
                            SqlRelationalTypeInterface::T_EQUAL_TO => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_GREATER_THAN => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_GREATER_THAN, $c);
                            },
                            SqlRelationalTypeInterface::T_GREATER_EQUAL_TO => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_GREATER_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_LESS_THAN => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_SMALLER, $c);
                            },
                            SqlRelationalTypeInterface::T_LESS_EQUAL_TO => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_SMALLER_EQUAL_TO, $c);
                            },
                            SqlRelationalTypeInterface::T_LIKE => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_LIKE, $c);
                            },
                            SqlRelationalTypeInterface::T_IN => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(SqlOperatorInterface::OP_IN, $c);
                            },
                            SqlRelationalTypeInterface::T_BETWEEN => function (ContainerInterface $c) {
                                return new SqlBetweenExpressionTemplate($c);
                            },
                            SqlRelationalTypeInterface::T_EXISTS => function (ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(SqlOperatorInterface::OP_EXISTS, $c);
                            },
                        ],
                    ]);
                },
                // Master SQL expression template
                'sql_expression_template' => function (ContainerInterface $c) {
                    return new SqlExpressionMasterTemplate($c->get('sql_expression_template_container'));
                },
                // Expression builder
                'sql_expression_builder' => function (ContainerInterface $c) {
                    return new ExpressionBuilder($c->get('sql_expression_builder_factories'));
                },
                'sql_expression_builder_factories' => function (ContainerInterface $c) {
                    return [
                            'lit'  => $c->get('sql_literal_expression_builder_factory'),
                            'var'  => $c->get('sql_variable_expression_builder_factory'),
                            'ef'   => $c->get('sql_entity_field_expression_builder_factory'),
                            'and'  => $c->get('sql_and_expression_builder_factory'),
                            'or'   => $c->get('sql_or_expression_builder_factory'),
                            'not'  => $c->get('sql_not_expression_builder_factory'),
                            'like' => $c->get('sql_like_expression_builder_factory'),
                            'eq'   => $c->get('sql_equal_to_expression_builder_factory'),
                            'gt'   => $c->get('sql_greater_than_expression_builder_factory'),
                            'ge'   => $c->get('sql_greater_equal_to_expression_builder_factory'),
                            'lt'   => $c->get('sql_less_than_expression_builder_factory'),
                            'le'   => $c->get('sql_less_equal_to_expression_builder_factory'),
                    ];
                },
                'sql_literal_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LiteralTerm($config['arguments'][0], 'literal');
                    });
                },
                'sql_variable_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new VariableTerm($config['arguments'][0], 'variable');
                    });
                },
                'sql_entity_field_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new EntityFieldTerm($config['arguments'][0], $config['arguments'][1], 'entity_field');
                    });
                },
                'sql_and_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogicalTypeInterface::T_AND);
                    });
                },
                'sql_or_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogicalTypeInterface::T_OR);
                    });
                },
                'sql_not_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogicalTypeInterface::T_NOT);
                    });
                },
                'sql_like_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        $arguments = $config['arguments'];
                        $negation = is_bool($arguments[0])
                            ? $arguments[0]
                            : false;
                        $terms = is_bool($arguments[0])
                            ? array_slice($arguments, 1)
                            : $arguments;

                        return new LogicalExpression($terms, $negation, SqlLogicalTypeInterface::T_LIKE);
                    });
                },
                'sql_equal_to_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        $arguments = $config['arguments'];
                        $negation = is_bool($arguments[0])
                            ? $arguments[0]
                            : false;
                        $terms = is_bool($arguments[0])
                            ? array_slice($arguments, 1)
                            : $arguments;

                        return new LogicalExpression($terms, $negation, SqlLogicalTypeInterface::T_EQUAL_TO);
                    });
                },
                'sql_greater_than_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false,
                            SqlLogicalTypeInterface::T_GREATER_THAN);
                    });
                },
                'sql_greater_equal_to_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false,
                            SqlLogicalTypeInterface::T_GREATER_EQUAL_TO);
                    });
                },
                'sql_less_than_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false,
                            SqlLogicalTypeInterface::T_LESS_THAN);
                    });
                },
                'sql_less_equal_to_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false,
                            SqlLogicalTypeInterface::T_LESS_EQUAL_TO);
                    });
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
