<?php

namespace RebelCode\Storage\Resource\Sql\FuncTest;

use RebelCode\Storage\Resource\Sql\Order as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class OrderTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Storage\Resource\Sql\Order';

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject('', '', false);

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Storage\Resource\Sql\OrderInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the constructor to assert whether the given data is correctly stored and retrieved.
     *
     * @since [*next-version*]
     */
    public function testConstructor()
    {
        $entity    = uniqid('entity-');
        $field     = uniqid('field-');
        $ascending = (bool) rand(0, 1);

        $subject = new TestSubject($entity, $field, $ascending);

        $this->assertEquals($entity, $subject->getEntity(), 'Set and retrieved entities do not match.');
        $this->assertEquals($field, $subject->getField(), 'Set and retrieved field do not match.');
        $this->assertEquals($ascending, $subject->isAscending(), 'Set and retrieved ascending flags do not match.');
    }

    /**
     * Tests the constructor with an invalid entity string to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testConstructorInvalidEntity()
    {
        $entity    = new stdClass();
        $field     = uniqid('field-');
        $ascending = (bool) rand(0, 1);

        $this->setExpectedException('InvalidArgumentException');

        new TestSubject($entity, $field, $ascending);
    }

    /**
     * Tests the constructor with an invalid field string to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testConstructorInvalidField()
    {
        $entity    = uniqid('entity-');
        $field     = new stdClass();
        $ascending = (bool) rand(0, 1);

        $this->setExpectedException('InvalidArgumentException');

        new TestSubject($entity, $field, $ascending);
    }

    /**
     * Tests the constructor with an invalid ascending flag to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testConstructorInvalidAscending()
    {
        $entity    = uniqid('entity-');
        $field     = uniqid('field-');
        $ascending = new stdClass();

        $this->setExpectedException('InvalidArgumentException');

        new TestSubject($entity, $field, $ascending);
    }
}
