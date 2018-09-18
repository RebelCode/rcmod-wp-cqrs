<?php

namespace RebelCode\Expression\FuncTest;

use RebelCode\Expression\EntityFieldTerm as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class EntityFieldTermTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\EntityFieldTerm';

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject('', '', '');

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Expression\VariableTermInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );

        $this->assertInstanceOf(
            'Dhii\Storage\Resource\Sql\EntityFieldInterface',
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
        $entity = uniqid('entity-');
        $field  = uniqid('field-');
        $type   = uniqid('type-');
        $key    = "$entity.$field";

        $subject = new TestSubject($entity, $field, $type);

        $this->assertEquals($entity, $subject->getEntity(), 'Set and retrieved entities do not match.');
        $this->assertEquals($field, $subject->getField(), 'Set and retrieved fields do not match.');
        $this->assertEquals($key, $subject->getKey(), 'Retrieved key is incorrect.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved types do not match.');
    }

    /**
     * Tests the constructor to assert whether the created instance's term type defaults correctly.
     *
     * @since [*next-version*]
     */
    public function testConstructorNoType()
    {
        $entity = uniqid('entity-');
        $field  = uniqid('field-');
        $key    = "$entity.$field";

        $subject = new TestSubject($entity, $field);

        $this->assertEquals($entity, $subject->getEntity(), 'Set and retrieved entities do not match.');
        $this->assertEquals($field, $subject->getField(), 'Set and retrieved fields do not match.');
        $this->assertEquals($key, $subject->getKey(), 'Retrieved key is incorrect.');
        $this->assertEquals(TestSubject::DEFAULT_TYPE, $subject->getType(), 'Type is not the default.');
    }

    /**
     * Tests the constructor with stringable entity and field instances to assert whether the key is correctly reduced
     * to string on retrieval.
     *
     * @since [*next-version*]
     */
    public function testConstructorStringable()
    {
        $entity = $this->mock('Dhii\Util\String\StringableInterface')->__toString(uniqid('entity-'))->new();
        $field  = $this->mock('Dhii\Util\String\StringableInterface')->__toString(uniqid('field-'))->new();
        $key    = $entity->__toString() . '.' . $field->__toString();

        $subject = new TestSubject($entity, $field);

        $this->assertEquals($entity, $subject->getEntity(), 'Set and retrieved entities do not match.');
        $this->assertEquals($field, $subject->getField(), 'Set and retrieved fields do not match.');
        $this->assertEquals($key, $subject->getKey(), 'Retrieved key is incorrect.');
        $this->assertEquals(TestSubject::DEFAULT_TYPE, $subject->getType(), 'Type is not the default.');
    }
}
