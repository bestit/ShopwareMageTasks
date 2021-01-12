<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Opcode;

use BestIt\Mage\TestGettersTrait;
use Mage\Runtime\Runtime;
use Mage\Task\AbstractTask;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use function assert;
use function implode;
use function uniqid;

/**
 * Test CleanReset.
 *
 * @package BestIt\Mage\Task\Opcode
 */
class CleanResetTest extends TestCase
{
    use TestGettersTrait;

    /**
     * Returns the asserts for the getter check.
     *
     * @see TestGettersTrait::testGetters
     *
     * @return array The first value is the getter property, and the second value is the value which is returned.
     */
    public function getGetterAsserts(): array
    {
        return [
            'description' => ['description', '[opcode] Calls the cleaner of .',],
            'name' => ['name', 'opcode/clean-reset',],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new CleanResetTask();
    }

    /**
     * Checks the full execute run.
     *
     * @param bool $withDelete
     *
     * @return void
     */
    public function testExecute(bool $withDelete = true): void
    {
        assert($this->fixture instanceof CleanResetTask);

        $this->fixture
            ->setRuntime($runtime = $this->createMock(Runtime::class))
            ->setOptions([
                'doc_root' => $docRoot = uniqid(),
                'urls' => [$url1 = 'http://example.com', $url2 = 'http://foo.example.com',],
                'without_delete' => !$withDelete,
            ]);

        if ($withDelete) {
            $commandRunsArguments = [
                ['curl -k -X GET ' . $url1 . '/apc_clear.php',],
                ['curl -k -X GET ' . $url2 . '/apc_clear.php',],
                ['rm -rf ' . $docRoot . '/apc_clear.php',],
            ];
        } else {
            $commandRunsArguments = [
                ['curl -k -X GET ' . $url1 . '/apc_clear.php',],
                ['curl -k -X GET ' . $url2 . '/apc_clear.php',],
            ];
        }

        $runtime
            ->expects(static::exactly(count($commandRunsArguments)))
            ->method('runCommand')
            ->withConsecutive(...$commandRunsArguments)
            ->willReturnOnConsecutiveCalls(
                $this->createMock(Process::class),
                $this->createMock(Process::class),
                $process = $this->createMock(Process::class),
            );

        $process
            ->expects($withDelete ? static::once() : static::never())
            ->method('isSuccessful')
            ->willReturn(true);

        static::assertTrue($this->fixture->execute());
    }

    /**
     * Checks the execute run without delete.
     *
     * @return void
     */
    public function testExecuteWithoutDelete(): void
    {
        $this->testExecute(false);
    }

    /**
     * Checks that the description contains the given urls.
     *
     * @return void
     */
    public function testGetDescriptionWithDocRootAndUrls(): void
    {
        assert($this->fixture instanceof CleanResetTask);

        $this->fixture->setOptions([
            'urls' => $urls = ['http://example.com', 'http://foo.example.com',],
        ]);

        static::assertSame(
            '[opcode] Calls the cleaner of ' . implode(', ', $urls) . '.',
            $this->fixture->getDescription(),
        );
    }

    /**
     * Checks the interface of the class.
     *
     * @return void
     */
    public function testInterface(): void
    {
        static::assertInstanceOf(AbstractTask::class, $this->fixture);
    }
}
