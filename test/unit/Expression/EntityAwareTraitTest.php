<?php

namespace RebelCode\Expression\FuncTest;

use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\EntityAwareTrait as TestSubject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class EntityAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\EntityAwareTrait';

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
     * Tests the internal entity setter and public entity getter methods to ensure correct assignment and retrieval of
     * the entity string.
     *
     * @since [*next-version*]
     */
    public function testGetSetEntity()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $entity = uniqid('entity-');

        $reflect->_setEntity($entity);

        $this->assertEquals($entity, $reflect->_getEntity(), 'Set and retrieved entities do not match.');
    }

    /**
     * Tests the internal entity setter and public entity getter methods to ensure correct assignment and retrieval of
     * the entity string.
     *
     * @since [*next-version*]
     */
    public function testGetSetEntityStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $entity = $this->getMockForAbstractClass('Dhii\Util\String\StringableInterface');

        $reflect->_setEntity($entity);

        $this->assertSame($entity, $reflect->_getEntity(), 'Set and retrieved entities do not match.');
    }

    /**
     * Tests the internal entity setter method with an invalid entity to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetEntityInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $entity = new stdClass();

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'), $this->anything(), null, $entity)
                ->willReturn(new InvalidArgumentException());

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setEntity($entity);
    }
}
