<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Misc;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Test DenyRobotsTxtTask
 *
 * @package BestIt\Mage\Task\Misc
 */
class DenyRobotsTxtTaskTest extends TestCase
{
    /**
     * This filesystem is injected per default.
     *
     * @var FilesystemInterface|null
     */
    private ?FilesystemInterface $filesystem = null;

    /**
     * The tested class.
     *
     * @var DenyRobotsTxtTask|null
     */
    private ?DenyRobotsTxtTask $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixture = new DenyRobotsTxtTask();

        $this->fixture->setFilesystem($this->filesystem = new Filesystem(new MemoryAdapter()));
    }

    /**
     * Checks if the robots.txt is created.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function testExecute(): void
    {
        $this->fixture->execute();

        static::assertSame(
            "User-agent: *\nDisallow: /\n",
            $this->filesystem->read('robots.txt'),
        );
    }

    /**
     * Checks if the robots.txt is created in the designated folder.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function testExecuteWithFolder(): void
    {
        $this->fixture->setOptions(['folder' => $folder = uniqid()]);

        $this->fixture->execute();

        static::assertSame(
            "User-agent: *\nDisallow: /\n",
            $this->filesystem->read($folder . '/robots.txt'),
        );
    }

    /**
     * Is a description returned?
     *
     * @return void
     */
    public function testGetDescription(): void
    {
        static::assertNotEmpty($this->fixture->getDescription());
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName(): void
    {
        static::assertSame('misc/deny-robots-txt', $this->fixture->getName());
    }
}
