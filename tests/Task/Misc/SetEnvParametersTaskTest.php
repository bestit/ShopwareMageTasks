<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Misc;

use BestIt\Mage\Task\Env\SetEnvParametersTask as BaseTask;
use PHPUnit\Framework\TestCase;

/**
 * Test SetEnvParametersTask
 *
 * @deprecated since version 0.7.0. To be removed in 1.0.0.
 * @package BestIt\Mage\Task\Misc
 */
class SetEnvParametersTaskTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var SetEnvParametersTask|null
     */
    private ?SetEnvParametersTask $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new SetEnvParametersTask();
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName(): void
    {
        static::assertSame('misc/set-env-parameters', $this->fixture->getName());
    }

    /**
     * Enforces BC for the new env namespace.
     *
     * @return void
     */
    public function testType(): void
    {
        static::assertInstanceOf(BaseTask::class, $this->fixture);
    }
}
