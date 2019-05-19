<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks;

use BestIt\Mage\Tasks\Opcode\CleanReset;
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
 * @package BestIt\Mage\Tasks
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
    protected function setUp()
    {
        $this->fixture = new CleanReset();
    }

    /**
     * Checks the full execute run.
     *
     * @return void
     */
    public function testExecute()
    {
        assert($this->fixture instanceof CleanReset);

        $this->fixture
            ->setRuntime($runtime = $this->createMock(Runtime::class))
            ->setOptions([
                'doc_root' => $docRoot = uniqid(),
                'urls' => [$url1 = 'http://example.com', $url2 = 'http://foo.example.com',],
            ]);

        $runtime
            ->expects(static::exactly(3))
            ->method('runCommand')
            ->withConsecutive(
                ['curl -k -X GET ' . $url1 . '/apc_clear.php',],
                ['curl -k -X GET ' . $url2 . '/apc_clear.php',],
                ['rm -rf ' . $docRoot . '/apc_clear.php',]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(Process::class),
                $this->createMock(Process::class),
                $process = $this->createMock(Process::class)
            );

        $process
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(true);

        static::assertTrue($this->fixture->execute());
    }

    /**
     * Checks that the description contains the given urls.
     *
     * @return void
     */
    public function testGetDescriptionWithDocRootAndUrls()
    {
        assert($this->fixture instanceof CleanReset);

        $this->fixture->setOptions([
            'urls' => $urls = ['http://example.com', 'http://foo.example.com',],
        ]);

        static::assertSame(
            '[opcode] Calls the cleaner of ' . implode(', ', $urls) . '.',
            $this->fixture->getDescription()
        );
    }

    /**
     * Checks the interface of the class.
     *
     * @return void
     */
    public function testInterface()
    {
        static::assertInstanceOf(AbstractTask::class, $this->fixture);
    }
}
