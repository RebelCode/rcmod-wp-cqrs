<?php

namespace RebelCode\Expression\FuncTest;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Expression as TestSubject;
use RebelCode\Expression\LiteralTerm;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ExpressionTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\Expression';

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = new TestSubject();

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
     * Tests the constructor with an array of terms to assert whether it is correctly returned on retrieval.
     *
     * @since [*next-version*]
     */
    public function testConstructorArrayTerms()
    {
        $terms = [
            new LiteralTerm(0),
            new LiteralTerm(0),
            new LiteralTerm(0),
        ];
        $type  = uniqid('type-');

        $subject = new TestSubject($terms, $type);

        $this->assertEquals($terms, $subject->getTerms(), 'Set and retrieved terms do not match.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved expression types do not match.');
    }

    /**
     * Tests the constructor with an stdClass of terms to assert whether it is correctly returned on retrieval.
     *
     * @since [*next-version*]
     */
    public function testConstructorStdClassTerms()
    {
        $terms = (object) [
            new LiteralTerm(0),
            new LiteralTerm(0),
            new LiteralTerm(0),
        ];
        $type  = uniqid('type-');

        $subject = new TestSubject($terms, $type);

        $this->assertSame($terms, $subject->getTerms(), 'Set and retrieved terms do not match.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved expression types do not match.');
    }

    /**
     * Tests the constructor with a traversable of terms to assert whether it is correctly returned on retrieval.
     *
     * @since [*next-version*]
     */
    public function testConstructorTraversableTerms()
    {
        $terms = $this->getMockForAbstractClass('Iterator');
        $type  = uniqid('type-');

        $subject = new TestSubject($terms, $type);

        $this->assertSame($terms, $subject->getTerms(), 'Set and retrieved terms do not match.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved expression types do not match.');
    }

    /**
     * Tests the constructor with a stringable type to assert whether it is correctly returned on retrieval.
     *
     * @since [*next-version*]
     */
    public function testConstructorStringableType()
    {
        $terms = [];
        $type  = $this->getMockForAbstractClass('Dhii\Util\String\StringableInterface');

        $subject = new TestSubject($terms, $type);

        $this->assertSame($terms, $subject->getTerms(), 'Set and retrieved terms do not match.');
        $this->assertEquals($type, $subject->getType(), 'Set and retrieved expression types do not match.');
    }

    /**
     * Tests the constructor with an invalid terms argument to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testConstructorInvalidTerms()
    {
        $terms = rand(0, 100);
        $type  = uniqid('type-');

        $this->setExpectedException('InvalidArgumentException');

        new TestSubject($terms, $type);
    }

    /**
     * Tests the constructor with an invalid type argument to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testConstructorInvalidType()
    {
        $terms = [];
        $type  = new stdClass();

        $this->setExpectedException('InvalidArgumentException');

        new TestSubject($terms, $type);
    }
}
