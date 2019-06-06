<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks;

use BestIt\Mage\Tasks\Env\CreateEnvFileTask;
use Dotenv\Dotenv;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;
use Mage\Task\Exception\ErrorException;
use PHPUnit\Framework\TestCase;
use function basename;
use function dirname;
use function file_get_contents;
use function uniqid;

/**
 * Test CreateEnvFileTask
 *
 * @backupGlobals enabled
 * @package BestIt\Mage\Tasks
 */
class CreateEnvFileTaskTest extends TestCase
{
    /**
     * The env file template for testing.
     */
    const TEST_FILE = __DIR__ . '/fixtures/createEnvFileTask/test.env';

    /**
     * This filesystem is injected per default.
     *
     * @var FilesystemInterface|null
     */
    private $filesystem;

    /**
     * The tested class.
     *
     * @var CreateEnvFileTask|null
     */
    private $fixture;

    /**
     * Loads environment vars from the test file.
     *
     * @return void
     */
    private function loadTestEnv()
    {
        Dotenv::create(dirname(self::TEST_FILE), basename(self::TEST_FILE))->overload();
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new CreateEnvFileTask();

        $this->fixture->setFilesystem($this->filesystem = new Filesystem(new MemoryAdapter()));
    }

    /**
     * Checks if an error is emitted, if there is env vars but an whitelist.
     *
     * @throws ErrorException
     *
     * @return void
     */
    public function testExecuteNoEnvVars()
    {
        static::expectException(ErrorException::class);

        $this->fixture->setOptions([
            'file' => $file = uniqid(),
            'whitelist' => [uniqid(),],
        ]);

        $this->fixture->execute();
    }

    /**
     * Checks if an error is emitted, if there is no file.
     *
     * @throws ErrorException
     *
     * @return void
     */
    public function testExecuteNoFile()
    {
        static::expectException(ErrorException::class);

        $this->fixture->setOptions(['file' => '']);

        $this->fixture->execute();
    }

    /**
     * Checks if every env var is saved in the file in the typical dotenv format.
     *
     * @throws ErrorException
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function testExecuteNoWhitelist()
    {
        $this->loadTestEnv();

        $this->fixture->setOptions(['file' => $file = uniqid()]);

        $this->fixture->execute();

        static::assertSame(
            file_get_contents(self::TEST_FILE),
            $this->filesystem->read($file)
        );
    }

    /**
     * Checks if every env var is saved in the file in the typical dotenv format because the whitelist is invalid.
     *
     * @throws ErrorException
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function testExecuteWithInvalidWhitelist()
    {
        $this->loadTestEnv();

        $this->fixture->setOptions([
            'file' => $file = uniqid(),
            'whitelist' => 'invalid-value'
        ]);

        $this->fixture->execute();

        static::assertSame(
            file_get_contents(self::TEST_FILE),
            $this->filesystem->read($file)
        );
    }

    /**
     * Checks if only whitelisted env vars are saved in the file in the typical dotenv format.
     *
     * @throws ErrorException
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function testExecuteWithValidWhitelist()
    {
        $this->loadTestEnv();

        $this->fixture->setOptions([
            'file' => $file = uniqid(),
            'whitelist' => [
                'foo.bar',
                'key2'
            ]
        ]);

        $this->fixture->execute();

        static::assertSame(
            "foo.bar=acme\nkey2=value2\n",
            $this->filesystem->read($file)
        );
    }

    /**
     * Checks the default return.
     *
     * @return void
     */
    public function testGetDefaults()
    {
        static::assertSame(
            [
                'file' => '.env',
                'whitelist' => [],
            ],
            $this->fixture->getDefaults()
        );
    }

    /**
     * Is a description returned?
     *
     * @return void
     */
    public function testGetDescription()
    {
        static::assertNotEmpty($this->fixture->getDescription());
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName()
    {
        static::assertSame('env/create-env-file', $this->fixture->getName());
    }
}
