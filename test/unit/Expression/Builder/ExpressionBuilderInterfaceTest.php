<?php

namespace RebelCode\Expression\Builder\ExpressionBuilderInterface\UnitTest;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Builder\ExpressionBuilderInterface as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ExpressionBuilderInterfaceTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\Builder\ExpressionBuilderInterface';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject|MockObject
     */
    public function createInstance()
    {
        return $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
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
    }
}
