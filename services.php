<?php

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Factory\FactoryInterface;
use Dhii\Factory\GenericCallbackFactory;
use Dhii\Output\TemplateInterface;
use Dhii\Storage\Resource\Sql\Expression\SqlLogicalTypeInterface as SqlLogType;
use Dhii\Storage\Resource\Sql\Expression\SqlOperatorInterface as SqlOp;
use Dhii\Storage\Resource\Sql\Expression\SqlRelationalTypeInterface as SqlRelType;
use Psr\Container\ContainerInterface;
use RebelCode\Expression\Builder\ExpressionBuilder;
use RebelCode\Expression\EntityFieldTerm;
use RebelCode\Expression\Expression;
use RebelCode\Expression\LiteralTerm;
use RebelCode\Expression\LogicalExpression;
use RebelCode\Expression\Renderer\Sql\SqlBetweenExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlFunctionExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlGenericFunctionExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlLiteralTermTemplate;
use RebelCode\Expression\Renderer\Sql\SqlOperatorExpressionTemplate;
use RebelCode\Expression\Renderer\Sql\SqlReferenceTermTemplate;
use RebelCode\Expression\SqlExpressionMasterTemplate;
use RebelCode\Expression\VariableTerm;
use RebelCode\Storage\Resource\Sql\Order;

return [
    /**
     * The WordPress database connection adapter.
     *
     * @since [*next-version*]
     *
     * @return wpdb
     */
    'wpdb'                                            => function (ContainerInterface $c) {
        global $wpdb;

        return $wpdb;
    },

    /*==============================================================*
     *   SQL Expression Rendering                                   |
     *==============================================================*/

    /**
     * Master SQL expression template.
     *
     * @since [*next-version*]
     *
     * @return TemplateInterface
     */
    'sql_expression_template'                         => function (ContainerInterface $c) {
        return new SqlExpressionMasterTemplate($c->get('sql_expression_template_container'));
    },

    /**
     * The container of SQL expression templates
     *
     * @since [*next-version*]
     *
     * @return ContainerInterface
     */
    'sql_expression_template_container'               => function (ContainerInterface $c) {
        $templates = [
            'literal'                                          => function (ContainerInterface $c) {
                return new SqlLiteralTermTemplate();
            },
            'set'                                              => function (ContainerInterface $c) {
                return new SqlFunctionExpressionTemplate('', $c->get('sql_expression_template'));
            },
            'variable'                                         => function (ContainerInterface $c) {
                return new SqlReferenceTermTemplate();
            },
            'entity_field'                                     => function (ContainerInterface $c) {
                return new SqlReferenceTermTemplate();
            },
            'is'                                               => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate('IS', $c->get('sql_expression_template'));
            },
            SqlExpressionMasterTemplate::K_GENERIC_FN_TEMPLATE => function (ContainerInterface $c) {
                return new SqlGenericFunctionExpressionTemplate($c->get('sql_expression_template'));
            },
            SqlLogType::T_AND                                  => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_AND, $c->get('sql_expression_template'));
            },
            SqlLogType::T_OR                                   => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_OR, $c->get('sql_expression_template'));
            },
            SqlLogType::T_NOT                                  => function (ContainerInterface $c) {
                return new SqlFunctionExpressionTemplate(
                    SqlOp::OP_NOT, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_EQUAL_TO                             => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_EQUAL_TO, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_GREATER_THAN                         => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_GREATER_THAN, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_GREATER_EQUAL_TO                     => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_GREATER_EQUAL_TO, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_LESS_THAN                            => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_SMALLER, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_LESS_EQUAL_TO                        => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_SMALLER_EQUAL_TO, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_LIKE                                 => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_LIKE, $c->get('sql_expression_template')
                );
            },
            SqlRelType::T_IN                                   => function (ContainerInterface $c) {
                return new SqlOperatorExpressionTemplate(
                    SqlOp::OP_IN, $c->get('sql_expression_template'));
            },
            SqlRelType::T_BETWEEN                              => function (ContainerInterface $c) {
                return new SqlBetweenExpressionTemplate($c->get('sql_expression_template'));
            },
            SqlRelType::T_EXISTS                               => function (ContainerInterface $c) {
                return new SqlFunctionExpressionTemplate(
                    SqlOp::OP_EXISTS, $c->get('sql_expression_template')
                );
            },
        ];

        return $c->get('container_factory')->make([ContainerFactoryInterface::K_DATA => $templates]);
    },

    /*==============================================================*
     *   SQL Expression Builder                                     |
     *==============================================================*/

    /**
     * SQL expression builder.
     *
     * @since [*next-version*]
     *
     * @return ExpressionBuilder
     */
    'sql_expression_builder'                          => function (ContainerInterface $c) {
        return new ExpressionBuilder($c->get('sql_expression_builder_factories'));
    },

    /**
     * The SQL expression builder factory mapping (called magic method name to factory service key).
     *
     * @since [*next-version*]
     *
     * @return array
     */
    'sql_expression_builder_factories'                => function (ContainerInterface $c) {
        return [
            'lit'  => $c->get('sql_literal_expression_builder_factory'),
            'var'  => $c->get('sql_variable_expression_builder_factory'),
            'ef'   => $c->get('sql_entity_field_expression_builder_factory'),
            'is'   => $c->get('sql_is_expression_builder_factory'),
            'and'  => $c->get('sql_and_expression_builder_factory'),
            'or'   => $c->get('sql_or_expression_builder_factory'),
            'not'  => $c->get('sql_not_expression_builder_factory'),
            'like' => $c->get('sql_like_expression_builder_factory'),
            'eq'   => $c->get('sql_equal_to_expression_builder_factory'),
            'gt'   => $c->get('sql_greater_than_expression_builder_factory'),
            'gte'  => $c->get('sql_greater_equal_to_expression_builder_factory'),
            'lt'   => $c->get('sql_less_than_expression_builder_factory'),
            'lte'  => $c->get('sql_less_equal_to_expression_builder_factory'),
            'in'   => $c->get('sql_in_expression_builder_factory'),
            'set'  => $c->get('sql_set_expression_builder_factory'),
            'fn'   => $c->get('sql_fn_expression_builder_factory'),
        ];
    },
    /**
     * The SQL literal expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_literal_expression_builder_factory'          => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LiteralTerm($arguments[0], 'literal');
        });
    },
    /**
     * The SQL literal set expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_set_expression_builder_factory'              => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) use ($c) {
            $arguments = $this->_containerGet($config, 'arguments');
            $values    = [];

            if (count($arguments) > 0) {
                $values = is_array($arguments[0])
                    ? $arguments[0]
                    : $arguments;
            }

            $terms = array_map(function ($value) use ($c) {
                return $c->get('sql_expression_builder')->lit($value);
            }, $values);

            return new Expression($terms, 'set');
        });
    },
    /**
     * The SQL variable expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_variable_expression_builder_factory'         => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new VariableTerm($arguments[0], 'variable');
        });
    },
    /**
     * The SQL entity field expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_entity_field_expression_builder_factory'     => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new EntityFieldTerm($arguments[0], $arguments[1], 'entity_field');
        });
    },
    /**
     * The SQL IS expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_is_expression_builder_factory'               => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, 'is');
        });
    },
    /**
     * The SQL AND expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_and_expression_builder_factory'              => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_AND);
        });
    },
    /**
     * The SQL OR expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_or_expression_builder_factory'               => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_OR);
        });
    },
    /**
     * The SQL NOT expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_not_expression_builder_factory'              => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_NOT);
        });
    },
    /**
     * The SQL LIKE expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_like_expression_builder_factory'             => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');
            $negation  = is_bool($arguments[0])
                ? $arguments[0]
                : false;
            $terms     = is_bool($arguments[0])
                ? array_slice($arguments, 1)
                : $arguments;

            return new LogicalExpression($terms, $negation, SqlLogType::T_LIKE);
        });
    },
    /**
     * The SQL "equal to" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_equal_to_expression_builder_factory'         => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');
            $negation  = is_bool($arguments[0])
                ? $arguments[0]
                : false;
            $terms     = is_bool($arguments[0])
                ? array_slice($arguments, 1)
                : $arguments;

            return new LogicalExpression($terms, $negation, SqlLogType::T_EQUAL_TO);
        });
    },
    /**
     * The SQL "greater than" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_greater_than_expression_builder_factory'     => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_GREATER_THAN);
        });
    },
    /**
     * The SQL "greater than or equal to" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_greater_equal_to_expression_builder_factory' => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_GREATER_EQUAL_TO);
        });
    },
    /**
     * The SQL "less than" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_less_than_expression_builder_factory'        => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_LESS_THAN);
        });
    },
    /**
     * The SQL "less than or equal to" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_less_equal_to_expression_builder_factory'    => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_LESS_EQUAL_TO);
        });
    },
    /**
     * The SQL "IN" expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_in_expression_builder_factory'               => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            return new LogicalExpression($arguments, false, SqlLogType::T_IN);
        });
    },
    /**
     * The SQL generic function expression builder factory service.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_fn_expression_builder_factory'               => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $arguments = $this->_containerGet($config, 'arguments');

            if (count($arguments) === 0) {
                throw $this->_createOutOfRangeException(
                    $this->__('Must have at least a function name argument for SQL function expression factory'),
                    null, null, $arguments
                );
            }

            $type  = 'sql_fn_' . $arguments[0];
            $terms = array_slice($arguments, 1);

            return new Expression($terms, $type);
        });
    },

    /*==============================================================*
     *   Misc. SQL Factories                                        |
     *==============================================================*/

    /**
     * The factory that creates SQL OrderInterface instances.
     *
     * @since [*next-version*]
     *
     * @return FactoryInterface
     */
    'sql_order_factory'                               => function (ContainerInterface $c) {
        return new GenericCallbackFactory(function ($config) {
            $field     = $this->_containerGet($config, 'field');
            $entity    = $this->_containerHas($config, 'entity')
                ? $this->_containerGet($config, 'entity')
                : null;
            $ascending = $this->_containerHas($config, 'ascending')
                ? $this->_containerGet($config, 'ascending')
                : true;

            return new Order($entity, $field, $ascending);
        });
    },
];
