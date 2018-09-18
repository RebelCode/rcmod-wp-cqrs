<?php

namespace RebelCode\Expression\UnitTest;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\AbstractTerm as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractTermTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\AbstractTerm';

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
            'Dhii\Expression\TermInterface',
            $subject,
            'Test subject does not implement expected interface.'
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

        $this->assertEquals($type, $subject->getType(), 'Set and retrieved term types do not match.');
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

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setType($type);
    }
}
