<?php

namespace RebelCode\Expression\FuncTest;

use Dhii\Output\TemplateInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Expression;
use RebelCode\Expression\LiteralTerm;
use RebelCode\Modular\Testing\Stub\DiContainerStub;
use Xpmock\TestCase;
use RebelCode\Expression\SqlExpressionMasterTemplate as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SqlExpressionMasterTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\SqlExpressionMasterTemplate';

    /**
     * Creates a template mock instance.
     *
     * @since [*next-version*]
     *
     * @param callable $callback The render output callable.
     *
     * @return MockObject|TemplateInterface
     */
    protected function createTemplate($callback)
    {
        $mock = $this->getMockForAbstractClass('Dhii\Output\TemplateInterface');
        $mock->method('render')
             ->willReturnCallback($callback);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject(new DiContainerStub());

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Output\TemplateInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the rendering functionality to assert if the test subject can properly delegate the rendering to a child
     * renderer using the term type string.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = new TestSubject(new DiContainerStub([
            'type1' => $this->createTemplate(function () {
                return 'ONE';
            }),
            'type2' => $this->createTemplate(function () {
                return 'TWO';
            }),
            'type3' => $this->createTemplate(function () {
                return 'THREE';
            }),
        ]));

        $literal = new LiteralTerm('test', 'type3');

        $expected = 'THREE';
        $actual   = $subject->render(['expression' => $literal]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the rendering functionality to assert if the test subject can properly delegate the rendering to the
     * function-specific renderer when the term type string of the given expression has the proper prefix.
     *
     * @since [*next-version*]
     */
    public function testRenderFunction()
    {
        $subject = new TestSubject(new DiContainerStub([
            'function' => $this->createTemplate(function () {
                return 'FN';
            }),
            'type1'    => $this->createTemplate(function () {
                return 'ONE';
            }),
            'type2'    => $this->createTemplate(function () {
                return 'TWO';
            }),
        ]));

        $expression = new Expression([], 'sql_fn_yoyo');

        $expected = 'FN';
        $actual   = $subject->render(['expression' => $expression]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the rendering functionality to assert if the test subject throws a template render exception when no
     * renderer corresponds with the term type string of the given render context expression.
     *
     * @since [*next-version*]
     */
    public function testRenderFunctionNoRenderer()
    {
        $subject = new TestSubject(new DiContainerStub([
            'type1' => $this->createTemplate(function () {
                return 'ONE';
            }),
            'type2' => $this->createTemplate(function () {
                return 'TWO';
            }),
            'type3' => $this->createTemplate(function () {
                return 'TWO';
            }),
        ]));

        $literal = new LiteralTerm(0, 'type4');

        $this->setExpectedException('Dhii\Output\Exception\RendererExceptionInterface');

        $subject->render(['expression' => $literal]);
    }
}
