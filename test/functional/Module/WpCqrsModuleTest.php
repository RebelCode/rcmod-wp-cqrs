<?php

namespace RebelCode\Storage\Resource\WordPress\Module\FuncTest;

use Dhii\Modular\Module\ModuleInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Container\ContainerInterface;
use RebelCode\Modular\Testing\ModuleTestCase;
use RebelCode\Storage\Resource\WordPress\Module\WpCqrsModule;

/**
 * Tests {@link RebelCode\Storage\Resource\WordPress\Module\WpCqrsModule}.
 *
 * @see   WpCqrsModule
 *
 * @since [*next-version*]
 */
class WpCqrsModuleTest extends ModuleTestCase
{
    /**
     * Returns the path to the module main file.
     *
     * @since [*next-version*]
     *
     * @return string The file path.
     */
    public function getModuleFilePath()
    {
        return __DIR__ . '/../../../module.php';
    }

    /**
     * Tests the `wpdb` service to assert if it can be retrieved from the container and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupWpdb()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService($module, 'wpdb', null, []);
    }

    /**
     * Tests the `sql_expression_template` service to assert if it can be retrieved from the container and if its type
     * is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlExpressionTemplate()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService($module, 'sql_expression_template', 'Dhii\Output\TemplateInterface', [
            'container_factory' => function (ContainerInterface $c) {
                return $this->mockContainerFactory();
            },
        ]);
    }

    /**
     * Tests the `sql_expression_template_container` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlExpressionTemplateContainer()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService($module, 'sql_expression_template_container', 'Psr\Container\ContainerInterface',
            [
                'container_factory' => function (ContainerInterface $c) {
                    return $this->mockContainerFactory();
                },
            ]
        );
    }

    /**
     * Tests the `sql_expression_builder` service to assert if it can be retrieved from the container and if its type
     * is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlExpressionBuilder()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService($module, 'sql_expression_builder',
            'RebelCode\Expression\Builder\ExpressionBuilder', []);
    }

    /**
     * Tests the `sql_expression_builder_factories` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlExpressionBuilderFactories()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_expression_builder_factories', 'array', []
        );
    }

    /**
     * Tests the `sql_literal_expression_builder_factory` service to assert if it can be retrieved from the container
     * and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlLiteralExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService($module, 'sql_literal_expression_builder_factory',
            'Dhii\Factory\FactoryInterface', []);
    }

    /**
     * Tests the `sql_set_expression_builder_factory` service to assert if it can be retrieved from the container and
     * if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlSetExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_set_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_variable_expression_builder_factory` service to assert if it can be retrieved from the container
     * and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlVariableExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_variable_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_entity_field_expression_builder_factory` service to assert if it can be retrieved from the
     * container and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlEntityFieldExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_entity_field_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_is_expression_builder_factory` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlIsExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_is_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_and_expression_builder_factory` service to assert if it can be retrieved from the container and
     * if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlAndExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_and_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_or_expression_builder_factory` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlOrExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_or_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_not_expression_builder_factory` service to assert if it can be retrieved from the container and
     * if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlNotExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_not_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_like_expression_builder_factory` service to assert if it can be retrieved from the container and
     * if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlLikeExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_like_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_equal_to_expression_builder_factory` service to assert if it can be retrieved from the container
     * and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlEqualToExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_equal_to_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_greater_than_expression_builder_factory` service to assert if it can be retrieved from the
     * container and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlGreaterThanExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_greater_than_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_greater_equal_to_expression_builder_factory` service to assert if it can be retrieved from the
     * container and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlGreaterEqualToExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_greater_equal_to_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_less_than_expression_builder_factory` service to assert if it can be retrieved from the container
     * and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlLessThanExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_less_than_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_less_equal_to_expression_builder_factory` service to assert if it can be retrieved from the
     * container and if its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlLessEqualToExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_less_equal_to_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_in_expression_builder_factory` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlInExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_in_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_fn_expression_builder_factory` service to assert if it can be retrieved from the container and if
     * its type is correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlFnExpressionBuilderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_fn_expression_builder_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    /**
     * Tests the `sql_order_factory` service to assert if it can be retrieved from the container and if its type is
     * correct.
     *
     * @since [*next-version*]
     */
    public function testSetupSqlOrderFactory()
    {
        /* @var $module MockObject|ModuleInterface */
        $module = $this->createModule($this->getModuleFilePath());

        $this->assertModuleHasService(
            $module, 'sql_order_factory', 'Dhii\Factory\FactoryInterface', []
        );
    }

    public function testRun()
    {
        /* @var $module MockObject|ModuleInterface */
        $module    = $this->createModule($this->getModuleFilePath());
        $container = $module->setup();

        $module->run($container);
    }
}
