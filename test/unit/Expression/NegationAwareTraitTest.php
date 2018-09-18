<?php

namespace RebelCode\Expression\FuncTest;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\NegationAwareTrait as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class NegationAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\NegationAwareTrait';

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
     * Tests the internal negation setter and public negation getter methods to ensure correct assignment and
     * retrieval of the negation flag when it is set to true.
     *
     * @since [*next-version*]
     */
    public function testGetSetNegationTrue()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $negation = true;

        $reflect->_setNegation($negation);

        $this->assertEquals($negation, $reflect->_getNegation(), 'Set and retrieved negation flags do not match.');
    }

    /**
     * Tests the internal negation setter and public negation getter methods to ensure correct assignment and
     * retrieval of the negation flag when it is set to false.
     *
     * @since [*next-version*]
     */
    public function testGetSetNegationFalse()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $negation = false;

        $reflect->_setNegation($negation);

        $this->assertEquals($negation, $reflect->_getNegation(), 'Set and retrieved negation flags do not match.');
    }

    /**
     * Tests the internal negation setter method with an invalid negation to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetNegationInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $negation = new stdClass();

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'), $this->anything(), null, $negation)
                ->willReturn(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setNegation($negation);
    }
}
