<?php

namespace RebelCode\Expression\FuncTest;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\TypeAwareTrait as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class TypeAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\TypeAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject|MockObject The new instance.
     */
    public function createInstance()
    {
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods([
                         '_createInvalidArgumentException',
                         '__',
                     ])
                     ->getMockForTrait();

        $mock->method('__')->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the internal type setter and public type getter methods to ensure correct assignment and retrieval of the
     * term type string.
     *
     * @since [*next-version*]
     */
    public function testGetSetType()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = uniqid('type-');

        $reflect->_setType($type);

        $this->assertEquals($type, $reflect->_getType(), 'Set and retrieved term types do not match.');
    }

    /**
     * Tests the internal type setter and public type getter methods to ensure correct assignment and retrieval of the
     * term type stringable instance.
     *
     * @since [*next-version*]
     */
    public function testGetSetTypeStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = $this->getMockForAbstractClass('Dhii\Util\String\StringableInterface');

        $reflect->_setType($type);

        $this->assertEquals($type, $reflect->_getType(), 'Set and retrieved term types do not match.');
    }

    /**
     * Tests the internal type setter method with an invalid type to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetTypeInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = new stdClass();

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'), $this->anything(), null, $type)
                ->willReturn(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setType($type);
    }
}
