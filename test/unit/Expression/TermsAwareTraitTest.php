<?php

namespace RebelCode\Expression\FuncTest;

use Dhii\Expression\TermInterface;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\TermsAwareTrait as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class TermsAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\TermsAwareTrait';

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
     * Creates a mock term instance.
     *
     * @since [*next-version*]
     *
     * @return TermInterface|MockObject
     */
    public function createTerm()
    {
        return $this->getMockForAbstractClass('Dhii\Expression\TermInterface');
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
     * Tests the terms internal setter and public getter methods with an array to assert whether the terms are
     * correctly stored and retrieved.
     *
     * @since [*next-version*]
     */
    public function testGetSetTermsArray()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $terms = [
            $this->createTerm(),
            $this->createTerm(),
            $this->createTerm(),
        ];

        $reflect->_setTerms($terms);

        $this->assertEquals($terms, $reflect->_getTerms(), 'Set and retrieved terms do not match');
    }

    /**
     * Tests the terms internal setter and public getter methods with an stdClass to assert whether the terms are
     * correctly stored and retrieved.
     *
     * @since [*next-version*]
     */
    public function testGetSetTermsStdClass()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $terms = (object) [
            $this->createTerm(),
            $this->createTerm(),
            $this->createTerm(),
        ];

        $reflect->_setTerms($terms);

        $this->assertEquals($terms, $reflect->_getTerms(), 'Set and retrieved terms do not match');
    }

    /**
     * Tests the terms internal setter and public getter methods with a traversable to assert whether the terms are
     * correctly stored and retrieved.
     *
     * @since [*next-version*]
     */
    public function testGetSetTermsTraversable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $terms = $this->getMockForAbstractClass('Iterator');

        $reflect->_setTerms($terms);

        $this->assertEquals($terms, $reflect->_getTerms(), 'Set and retrieved terms do not match');
    }

    /**
     * Tests the terms internal setter and public getter methods with an invalid argument to assert whether an
     * exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetTermsInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $terms = $this->getMockForAbstractClass('Psr\Container\ContainerInterface');

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'), $this->anything(), null, $terms)
                ->willReturn(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setTerms($terms);
    }
}
