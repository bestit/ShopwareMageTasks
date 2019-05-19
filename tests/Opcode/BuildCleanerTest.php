<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks;

use BestIt\Mage\Tasks\Opcode\BuildCleaner;
use Mage\Runtime\Runtime;
use Mage\Task\AbstractTask;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use function assert;
use function sprintf;
use function uniqid;

/**
 * Test BuildCleaner.
 *
 * @package BestIt\Mage\Tasks
 */
class BuildCleanerTest extends TestCase
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
            'description' => ['description', '[opcode] Creates the cleaner in .',],
            'name' => ['name', 'opcode/build-cleaner',],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new BuildCleaner();
    }

    /**
     * Checks the full execute run.
     *
     * @return void
     */
    public function testExecute()
    {
        assert($this->fixture instanceof BuildCleaner);

        $this->fixture
            ->setRuntime($runtime = $this->createMock(Runtime::class))
            ->setOptions(['doc_root' => $docRoot = uniqid(),]);

        $template = "<?php

declare(strict_types=1);

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
}

if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}";

        $runtime
            ->expects(static::once())
            ->method('runCommand')
            ->with(sprintf(
                'echo "%s" > %s/apc_clear.php',
                $template,
                $docRoot
            ))
            ->willReturn($process = $this->createMock(Process::class));

        $process
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(true);

        static::assertTrue($this->fixture->execute());
    }

    /**
     * Checks that the description contains the given doc root.
     *
     * @return void
     */
    public function testGetDescriptionWithDocRoot()
    {
        assert($this->fixture instanceof BuildCleaner);

        $this->fixture->setOptions(['doc_root' => $docRoot = uniqid(),]);

        static::assertSame('[opcode] Creates the cleaner in ' . $docRoot, $this->fixture->getDescription());
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
