<?php

namespace RebelCode\Expression\FuncTest;

use Xpmock\TestCase;
use RebelCode\Expression\LiteralTerm as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class LiteralTermTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\LiteralTerm';

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject(null);

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Expression\LiteralTermInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the constructor to assert whether it correctly stores the instance's state.
     *
     * @since [*next-version*]
     */
    public function testConstructor()
    {
        $value = uniqid('value-');
        $type  = uniqid('type-');

        $subject = new TestSubject($value, $type);

        $this->assertEquals($value, $subject->getValue(), 'Set and retrieved values do not match.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved types do not match.');
    }

    /**
     * Tests the constructor to assert whether the created instance's term type defaults correctly.
     *
     * @since [*next-version*]
     */
    public function testConstructorNoType()
    {
        $value = uniqid('value-');

        $subject = new TestSubject($value);

        $this->assertEquals($value, $subject->getValue(), 'Set and retrieved values do not match.');
        $this->assertEquals(TestSubject::DEFAULT_TYPE, $subject->getType(), 'Type is not the default.');
    }
}
