<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use Mage\Runtime\Runtime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use function implode;
use function mt_rand;
use function sprintf;
use function uniqid;

/**
 * Test SubComposerInstallTask
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class SubComposerInstallTaskTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var SubComposerInstallTask|null
     */
    private ?SubComposerInstallTask $fixture = null;

    /**
     * The injected runtime.
     *
     * @var Runtime|MockObject|null
     */
    private Runtime|MockObject|null $runtime = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new SubComposerInstallTask();

        $this->fixture->setRuntime($this->runtime = $this->createMock(Runtime::class));

        $this->runtime
            ->expects(static::any())
            ->method('getMergedOption')
            ->with('composer')
            ->willReturn([]);
    }

    /**
     * Checks the full method with two composer calls.
     *
     * @return void
     */
    public function testExecuteFull(): void
    {
        $this->fixture->setOptions([
            'flags' => '-o',
            'globs' => [
                __DIR__ . '/fixtures/SubComposerInstallTask/*/composer.json',
                __DIR__ . '/fixtures/SubComposerInstallTask/folder2/*/*/folder5/composer.json',
            ],
            'path' => 'composer.phar',
            'timeout' => $timeout = mt_rand(1, 10000),
        ]);

        $this->runtime
            ->expects(static::exactly(2))
            ->method('runCommand')
            ->withConsecutive(
                [
                    'composer.phar install -o -d "' . __DIR__ . '/fixtures/' .
                    'SubComposerInstallTask/folder1"',
                    $timeout,
                ],
                [
                    'composer.phar install -o -d "' . __DIR__ . '/fixtures/' .
                    'SubComposerInstallTask/folder2/folder3/folder4/folder5"',
                    $timeout,
                ],
            )->willReturnOnConsecutiveCalls(
                $process1 = $this->createMock(Process::class),
                $process2 = $this->createMock(Process::class),
            );

        $process1
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(false);

        $process2
            ->expects(static::once())
            ->method('isSuccessful')
            ->willReturn(true);

        static::assertFalse($this->fixture->execute());
    }

    /**
     * Checks the default return.
     *
     * @return void
     */
    public function testGetDefaults(): void
    {
        static::assertSame(
            [
                'globs' => [],
            ],
            $this->fixture->getDefaults(),
        );
    }

    /**
     * Is a description returned?
     *
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->fixture->setOptions(['globs' => $globs = [uniqid(), uniqid()]]);

        static::assertSame(
            sprintf('[Composer] Sub-Install of %s', implode(',', $globs)),
            $this->fixture->getDescription(),
        );
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName(): void
    {
        static::assertSame('composer/sub-install', $this->fixture->getName());
    }
}
