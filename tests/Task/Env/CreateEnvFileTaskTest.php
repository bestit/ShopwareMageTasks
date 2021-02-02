<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Env;

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryInterface;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;
use Mage\Task\Exception\ErrorException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function basename;
use function dirname;
use function file_get_contents;
use function uniqid;

/**
 * Test CreateEnvFileTask
 *
 * @backupGlobals enabled
 * @package BestIt\Mage\Task\Env
 */
class CreateEnvFileTaskTest extends TestCase
{
    /**
     * The env file template for testing.
     */
    public const TEST_FILE = __DIR__ . '/fixtures/createEnvFileTask/test.env';

    /**
     * The mocked RepositoryInterface
     *
     * @var RepositoryInterface|MockObject
     */
    private $envRepository;

    /**
     * This filesystem is injected per default.
     *
     * @var FilesystemInterface|null
     */
    private ?FilesystemInterface $filesystem = null;

    /**
     * The tested class.
     *
     * @var CreateEnvFileTask|null
     */
    private ?CreateEnvFileTask $fixture = null;

    /**
     * Backup of global env variables.
     *
     * @var array $envVariableBackup
     */
    private array $envVariableBackup;

    /**
     * Loads environment vars from the test file.
     *
     * @return void
     */
    private function loadTestEnv(): void
    {
        Dotenv::createImmutable(dirname(self::TEST_FILE), basename(self::TEST_FILE))->load();
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->envVariableBackup = $_ENV;
        $_ENV = [];

        $this->fixture = new CreateEnvFileTask();

        $this->fixture->setFilesystem($this->filesystem = new Filesystem(new MemoryAdapter()));
    }

    /**
     * Tears down the test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $_ENV = $this->envVariableBackup;
    }

    /**
     * Checks if an error is emitted, if there is env vars but an whitelist.
     *
     * @throws ErrorException on successful test
     *
     * @return void
     */
    public function testExecuteNoEnvVars(): void
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
     * @throws ErrorException on successful test
     *
     * @return void
     */
    public function testExecuteNoFile(): void
    {
        static::expectException(ErrorException::class);

        $this->fixture->setOptions(['file' => '']);

        $this->fixture->execute();
    }

    /**
     * Checks if every env var is saved in the file in the typical dotenv format.
     *
     * @throws ErrorException when no env file is given
     * @throws FileNotFoundException when no file can be found
     *
     * @return void
     */
    public function testExecuteNoWhitelist(): void
    {
        $this->loadTestEnv();

        $this->fixture->setOptions(['file' => $file = uniqid()]);

        $this->fixture->execute();

        static::assertSame(
            file_get_contents(self::TEST_FILE),
            $this->filesystem->read($file),
        );
    }

    /**
     * Checks if every env var is saved in the file in the typical dotenv format because the whitelist is invalid.
     *
     * @throws ErrorException when no env file is given
     * @throws FileNotFoundException when no file can be found
     *
     * @return void
     */
    public function testExecuteWithInvalidWhitelist(): void
    {
        $this->loadTestEnv();

        $this->fixture->setOptions([
            'file' => $file = uniqid(),
            'whitelist' => 'invalid-value',
        ]);

        $this->fixture->execute();

        static::assertSame(
            file_get_contents(self::TEST_FILE),
            $this->filesystem->read($file),
        );
    }

    /**
     * Checks if only whitelisted env vars are saved in the file in the typical dotenv format.
     *
     * @throws ErrorException when no env file is given
     * @throws FileNotFoundException when no file can be found
     *
     * @return void
     */
    public function testExecuteWithValidWhitelist(): void
    {
        $this->loadTestEnv();

        $this->fixture->setOptions([
            'file' => $file = uniqid(),
            'whitelist' => [
                'foo.bar',
                'key2',
                'key4',
            ],
        ]);

        $this->fixture->execute();

        static::assertSame(
            "foo.bar=\"acme\"\nkey2=\"value2\"\nkey4=0\n",
            $this->filesystem->read($file),
        );
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
                'file' => '.env',
                'whitelist' => [],
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
        static::assertNotEmpty($this->fixture->getDescription());
    }

    /**
     * Is a name returned.
     *
     * @return void
     */
    public function testGetName(): void
    {
        static::assertSame('env/create-env-file', $this->fixture->getName());
    }
}
