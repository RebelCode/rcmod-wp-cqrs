<?php

namespace RebelCode\Expression\Builder\FuncTest;

use Dhii\Exception\InternalException;
use Dhii\Factory\Exception\CouldNotMakeException;
use Dhii\Factory\FactoryInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Builder\ExpressionBuilder as TestSubject;
use RebelCode\Expression\Expression;
use RebelCode\Expression\LiteralTerm;
use RebelCode\Expression\VariableTerm;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ExpressionBuilderTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\Builder\ExpressionBuilder';

    /**
     * Creates a mock factory instance.
     *
     * @since [*next-version*]
     *
     * @param mixed $result The object that the factory makes.
     *
     * @return MockObject|FactoryInterface
     */
    protected function createFactory($result)
    {
        $mock = $this->getMockBuilder('Dhii\Factory\FactoryInterface')
                     ->setMethods(['make'])
                     ->getMockForAbstractClass();

        $mock->method('make')->willReturn($result);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject([]);

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'RebelCode\Expression\Builder\ExpressionBuilderInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the magic __call() method to assert whether it calls the build() method with the correct arguments.
     *
     * @since [*next-version*]
     */
    public function testCall()
    {
        $type = 'one';
        $arg1 = uniqid('arg1-');
        $arg2 = uniqid('arg2-');

        $expected = uniqid('expected-');

        $subject = $this->getMockBuilder('RebelCode\Expression\Builder\ExpressionBuilder')
                        ->setMethods(['build'])
                        ->disableOriginalConstructor()
                        ->getMock();
        $subject->expects($this->once())
                ->method('build')
                ->with($type, [$arg1, $arg2])
                ->willReturn($expected);

        $actual = $subject->one($arg1, $arg2);

        $this->assertSame($expected, $actual, 'The __call method did not return the build result.');
    }

    /**
     * Tests the build method to assert whether the subject correctly invokes the corresponding factories and returns
     * the results.
     *
     * @since [*next-version*]
     *
     * @throws InternalException
     */
    public function testBuild()
    {
        $one   = new VariableTerm('var');
        $two   = new Expression([], 'exp');
        $three = new LiteralTerm('value');

        $subject = new TestSubject([
            'one'   => $this->createFactory($one),
            'two'   => $this->createFactory($two),
            'three' => $this->createFactory($three),
        ]);

        $this->assertSame($one, $subject->build('one'), 'Created instance is incorrect.');
        $this->assertSame($two, $subject->build('two'), 'Created instance is incorrect.');
        $this->assertSame($three, $subject->build('three'), 'Created instance is incorrect.');
    }

    /**
     * Tests the build method to assert whether the subject correctly throws an internal exception if a factory
     * encounters an error while making the instance.
     *
     * @since [*next-version*]
     *
     * @throws InternalException
     */
    public function testBuildMakeError()
    {
        $factory = $this->createFactory(null);
        $factory->expects($this->once())
                ->method('make')
                ->willThrowException(new CouldNotMakeException());

        $subject = new TestSubject([
            'one' => $factory,
        ]);

        $this->setExpectedException('Dhii\Exception\InternalExceptionInterface');

        $subject->build('one');
    }

    /**
     * Tests the build method to assert whether the subject correctly throws an internal exception if no factory
     * was found that corresponds to the build type.
     *
     * @since [*next-version*]
     *
     * @throws InternalException
     */
    public function testBuildNoFactory()
    {
        $one   = new VariableTerm('var');
        $two   = new Expression([], 'exp');
        $three = new LiteralTerm('value');

        $subject = new TestSubject([
            'one'   => $this->createFactory($one),
            'two'   => $this->createFactory($two),
            'three' => $this->createFactory($three),
        ]);

        $this->setExpectedException('Dhii\Exception\InternalExceptionInterface');

        $subject->build('four');
    }

    /**
     * Tests the build method to assert whether the corresponding factory receives the build arguments in the config.
     *
     * @since [*next-version*]
     *
     * @throws InternalException
     */
    public function testBuildFactoryConfig()
    {
        $arguments = [
            'arg1' => uniqid('arg1-'),
            'arg2' => uniqid('arg2-'),
        ];
        $config    = [
            'arguments' => $arguments,
        ];

        $factory = $this->createFactory(null);
        $factory->expects($this->once())
                ->method('make')
                ->with($config)
                ->willReturn(null);

        $subject = new TestSubject([
            'one' => $factory,
        ]);

        $subject->build('one', $arguments);
    }
}
