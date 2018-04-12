<?php

namespace RebelCode\Storage\Resource\WordPress\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Data\Container\ContainerGetCapableTrait;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Data\Container\CreateContainerExceptionCapableTrait;
use Dhii\Data\Container\CreateNotFoundExceptionCapableTrait;
use Dhii\Data\Object\NormalizeKeyCapableTrait;
use Dhii\Factory\GenericCallbackFactory;
use Dhii\Storage\Resource\Sql\Expression\SqlLogicalTypeInterface as SqlLogType;
use Dhii\Storage\Resource\Sql\Expression\SqlOperatorInterface as SqlOp;
use Dhii\Storage\Resource\Sql\Expression\SqlRelationalTypeInterface as SqlRelType;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
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
use RebelCode\Expression\SqlExpressionMasterTemplate;
use RebelCode\Expression\VariableTerm;
use RebelCode\Modular\Module\AbstractBaseModule;
use RebelCode\Storage\Resource\Sql\Order;

/**
 * WordPress CQRS module class.
 *
 * @since [*next-version*]
 */
class WpCqrsModule extends AbstractBaseModule
{
    /*
     * @since [*next-version*]
     */
    use ContainerGetCapableTrait;

    /*
     * @since [*next-version*]
     */
    use ContainerHasCapableTrait;

    /*
     * @since [*next-version*]
     */
    use NormalizeKeyCapableTrait;

    /*
     * @since [*next-version*]
     */
    use NormalizeStringCapableTrait;

    /*
     * @since [*next-version*]
     */
    use CreateContainerExceptionCapableTrait;

    /*
     * @since [*next-version*]
     */
    use CreateNotFoundExceptionCapableTrait;

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
                'wpdb'                                            => function (ContainerInterface $c) {
                    global $wpdb;

                    return $wpdb;
                },
                'wpdb_table_prefixer'                             => function (ContainerInterface $c) {
                    $wpdb = $c->get('wpdb');

                    return function ($table) use ($wpdb) {
                        return $wpdb->prefix . $table;
                    };
                },
                'wpdb_table_map_prefixer'                         => function (ContainerInterface $c) {
                    $prefixer = $c->get('wpdb_table_prefixer');

                    return function ($tables) use ($prefixer) {
                        $prefixed = [];

                        foreach ($tables as $_alias => $_table) {
                            $prefixed[$_alias] = $prefixer($_table);
                        }

                        return $prefixed;
                    };
                },
                // Container with all SQL expression templates
                'sql_expression_template_container'               => function (ContainerInterface $c) {
                    return $c->get('container_factory')->make([
                        'definitions' => [
                            'literal'                      => function (ContainerInterface $c) {
                                return new SqlLiteralTermTemplate();
                            },
                            'variable'                     => function (ContainerInterface $c) {
                                return new SqlReferenceTermTemplate();
                            },
                            'entity_field'                 => function (ContainerInterface $c) {
                                return new SqlReferenceTermTemplate();
                            },
                            SqlLogType::T_AND              => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_AND, $c->get('sql_expression_template_container'));
                            },
                            SqlLogType::T_OR               => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_OR, $c->get('sql_expression_template_container'));
                            },
                            SqlLogType::T_NOT              => function (ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(
                                    SqlOp::OP_NOT, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_EQUAL_TO         => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_EQUAL_TO, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_GREATER_THAN     => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_GREATER_THAN, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_GREATER_EQUAL_TO => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_GREATER_EQUAL_TO, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_LESS_THAN        => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_SMALLER, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_LESS_EQUAL_TO    => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_SMALLER_EQUAL_TO, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_LIKE             => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_LIKE, $c->get('sql_expression_template_container')
                                );
                            },
                            SqlRelType::T_IN               => function (ContainerInterface $c) {
                                return new SqlOperatorExpressionTemplate(
                                    SqlOp::OP_IN, $c->get('sql_expression_template_container'));
                            },
                            SqlRelType::T_BETWEEN          => function (ContainerInterface $c) {
                                return new SqlBetweenExpressionTemplate($c->get('sql_expression_template_container'));
                            },
                            SqlRelType::T_EXISTS           => function (ContainerInterface $c) {
                                return new SqlFunctionExpressionTemplate(
                                    SqlOp::OP_EXISTS, $c->get('sql_expression_template_container')
                                );
                            },
                        ],
                    ]);
                },
                // Master SQL expression template
                'sql_expression_template'                         => function (ContainerInterface $c) {
                    return new SqlExpressionMasterTemplate($c->get('sql_expression_template_container'));
                },
                // Expression builder
                'sql_expression_builder'                          => function (ContainerInterface $c) {
                    return new ExpressionBuilder($c->get('sql_expression_builder_factories'));
                },
                'sql_expression_builder_factories'                => function (ContainerInterface $c) {
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
                'sql_literal_expression_builder_factory'          => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LiteralTerm($config['arguments'][0], 'literal');
                    });
                },
                'sql_variable_expression_builder_factory'         => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new VariableTerm($config['arguments'][0], 'variable');
                    });
                },
                'sql_entity_field_expression_builder_factory'     => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new EntityFieldTerm($config['arguments'][0], $config['arguments'][1], 'entity_field');
                    });
                },
                'sql_and_expression_builder_factory'              => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_AND);
                    });
                },
                'sql_or_expression_builder_factory'               => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_OR);
                    });
                },
                'sql_not_expression_builder_factory'              => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_NOT);
                    });
                },
                'sql_like_expression_builder_factory'             => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        $arguments = $config['arguments'];
                        $negation = is_bool($arguments[0])
                            ? $arguments[0]
                            : false;
                        $terms = is_bool($arguments[0])
                            ? array_slice($arguments, 1)
                            : $arguments;

                        return new LogicalExpression($terms, $negation, SqlLogType::T_LIKE);
                    });
                },
                'sql_equal_to_expression_builder_factory'         => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        $arguments = $config['arguments'];
                        $negation = is_bool($arguments[0])
                            ? $arguments[0]
                            : false;
                        $terms = is_bool($arguments[0])
                            ? array_slice($arguments, 1)
                            : $arguments;

                        return new LogicalExpression($terms, $negation, SqlLogType::T_EQUAL_TO);
                    });
                },
                'sql_greater_than_expression_builder_factory'     => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_GREATER_THAN);
                    });
                },
                'sql_greater_equal_to_expression_builder_factory' => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_GREATER_EQUAL_TO);
                    });
                },
                'sql_less_than_expression_builder_factory'        => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_LESS_THAN);
                    });
                },
                'sql_less_equal_to_expression_builder_factory'    => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        return new LogicalExpression($config['arguments'], false, SqlLogType::T_LESS_EQUAL_TO);
                    });
                },
                'sql_order_factory'                               => function (ContainerInterface $c) {
                    return new GenericCallbackFactory(function ($config) {
                        $field = $this->_containerGet($config, 'field');
                        $entity = $this->_containerHas($config, 'entity')
                            ? $this->_containerGet($config, 'entity')
                            : null;
                        $ascending = $this->_containerHas($config, 'ascending')
                            ? $this->_containerGet($config, 'ascending')
                            : true;

                        return new Order($entity, $field, $ascending);
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
