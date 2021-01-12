<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

use Mage\Runtime\Runtime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * Test ApplyMigrationsTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
class ApplyMigrationsTaskTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var ApplyMigrationsTask|null
     */
    protected ?ApplyMigrationsTask $fixture = null;

    /**
     * Returns the asserts for the getter check.
     *
     * @return array The first value is the getter property, and the second value is the value which is returned.
     */
    public function getGetterAsserts(): array
    {
        return [
            'defaults' => ['defaults', ['timeout' => 120,],],
            'description' => ['description', '[Shopware] Apply migrations.',],
            'name' => ['name', 'shopware/migrate',],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new ApplyMigrationsTask();
    }

    /**
     * Checks that the mage task is called with the correct options.
     *
     * @return void
     */
    public function testExecuteWithCustomValues(): void
    {
        assert($this->fixture instanceof ApplyMigrationsTask);

        $this->fixture->setRuntime($runtime = $this->createMock(Runtime::class));

        $runtime
            ->expects(static::once())
            ->method('runRemoteCommand')
            ->with(
                'php ./php5/ApplyDeltas.php --tablesuffix="foobar" --migrationpath="./migrations/" ' .
                    '--shoppath="./shopware" --mode=update',
                true,
                120,
            )
            ->willReturn($process = $this->createMock(Process::class));

        $process
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(false);

        $this->fixture->setOptions([
            'migration_dir' => 'migrations',
            'script_dir' => 'php5',
            'shop_path' => './shopware',
            'table_suffix' => 'foobar',
        ]);

        static::assertFalse($this->fixture->execute());
    }

    /**
     * Checks that the defaults are correctly used in the execute call.
     *
     * @return void
     */
    public function testExecuteWithDefaults(): void
    {
        assert($this->fixture instanceof ApplyMigrationsTask);

        $this->fixture->setRuntime($runtime = $this->createMock(Runtime::class));

        $runtime
            ->expects(static::once())
            ->method('runRemoteCommand')
            ->with(
                'php ./scripts/ApplyDeltas.php --tablesuffix="bestit" --migrationpath="./sql/" ' .
                    '--shoppath="." --mode=update',
                true,
                120,
            )
            ->willReturn($process = $this->createMock(Process::class));

        $process
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(true);

        $this->fixture->setOptions();

        static::assertTrue($this->fixture->execute());
    }
}
