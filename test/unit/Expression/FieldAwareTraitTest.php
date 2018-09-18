<?php

namespace RebelCode\Expression\FuncTest;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\FieldAwareTrait as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class FieldAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\FieldAwareTrait';

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
     * Tests the internal field setter and public field getter methods to ensure correct assignment and retrieval of
     * the field string.
     *
     * @since [*next-version*]
     */
    public function testGetSetField()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $field = uniqid('field-');

        $reflect->_setField($field);

        $this->assertEquals($field, $reflect->_getField(), 'Set and retrieved fields do not match.');
    }

    /**
     * Tests the internal field setter and public field getter methods to ensure correct assignment and retrieval of
     * the field string.
     *
     * @since [*next-version*]
     */
    public function testGetSetFieldStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $field = $this->getMockForAbstractClass('Dhii\Util\String\StringableInterface');

        $reflect->_setField($field);

        $this->assertSame($field, $reflect->_getField(), 'Set and retrieved fields do not match.');
    }

    /**
     * Tests the internal field setter method with an invalid field to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetFieldInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $field = new stdClass();

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'), $this->anything(), null, $field)
                ->willReturn(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setField($field);
    }
}
