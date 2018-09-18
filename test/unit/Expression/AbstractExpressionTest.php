<?php

namespace RebelCode\Expression\UnitTest;

use Dhii\Expression\TermInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\AbstractExpression as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractExpressionTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\AbstractExpression';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject|TestSubject
     */
    public function createInstance(array $methods = [])
    {
        return $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                    ->setMethods($methods)
                    ->getMockForAbstractClass();
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

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Expression\ExpressionInterface',
            $subject,
            'Test subject does not implement expected interface.'
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
            $this->createTerm()
        ];

        $reflect->_setTerms($terms);

        $this->assertEquals($terms, $subject->getTerms(), 'Set and retrieved terms do not match');
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
            $this->createTerm()
        ];

        $reflect->_setTerms($terms);

        $this->assertEquals($terms, $subject->getTerms(), 'Set and retrieved terms do not match');
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

        $this->assertEquals($terms, $subject->getTerms(), 'Set and retrieved terms do not match');
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

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setTerms($terms);
    }
}
