<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks;

use Mage\Runtime\Runtime;
use Mage\Task\AbstractTask;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use function assert;
use function ucfirst;

/**
 * Helps you test the getters of the task.
 *
 * @package BestIt\Mage\Tasks
 */
trait TestGettersTrait
{
    /**
     * The tested class.
     *
     * @var AbstractTask|null
     */
    protected $fixture;

    /**
     * Asserts that two variables have the same type and value.
     *
     * Used on objects, it asserts that two variables reference the same object.
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param mixed $message
     *
     * @return void
     */
    abstract public static function assertSame($expected, $actual, $message = '');

    /**
     * Returns a test double for the specified class.
     *
     * @param string $originalClassName
     * @throws Exception
     *
     * @return MockObject
     */
    abstract protected function createMock($originalClassName);

    /**
     * Returns the asserts for the getter check.
     *
     * @see TestGettersTrait::testGetters
     *
     * @return array The first value is the getter property, and the second value is the value which is returned.
     */
    abstract public function getGetterAsserts(): array;

    /**
     * Checks that the getters return the correct value.
     *
     * @dataProvider getGetterAsserts
     *
     * @param string $getterProperty
     * @param mixed $value
     *
     * @return void
     */
    public function testGetters(string $getterProperty, $value)
    {
        assert($this->fixture instanceof AbstractTask);

        $this->fixture->setRuntime($runtime = $this->createMock(Runtime::class));

        static::assertSame($value, $this->fixture->{'get' . ucfirst($getterProperty)}());
    }
}
