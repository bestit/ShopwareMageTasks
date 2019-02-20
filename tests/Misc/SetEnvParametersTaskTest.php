<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use BestIt\Mage\Tasks\Env\SetEnvParametersTask as BaseTask;
use PHPUnit\Framework\TestCase;

/**
 * Test SetEnvParametersTask
 *
 * @package BestIt\Mage\Tasks\Misc
 * @deprecated since version 0.7.0. To be removed in 1.0.0.
 */
class SetEnvParametersTaskTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var SetEnvParametersTask|null
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new SetEnvParametersTask();
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName()
    {
        static::assertSame('misc/set-env-parameters', $this->fixture->getName());
    }

    /**
     * Enforces BC for the new env namespace.
     *
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(BaseTask::class, $this->fixture);
    }
}
