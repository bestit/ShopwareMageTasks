<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Env;

use BestIt\Mage\Tasks\Shopware\AbstractTask;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Mage\Task\Exception\ErrorException;
use function getcwd;
use function in_array;

/**
 * Creates a local environment file (relative to the working directory) for the actual php environment.
 *
 * @package BestIt\Mage\Tasks\Env
 */
class CreateEnvFileTask extends AbstractTask
{
    /**
     * This file system is registered to the currenct working directory.
     *
     * This property is filled with lazy loading by the getter.
     *
     * @var FilesystemInterface|null
     */
    private $filesystem = null;

    /**
     * Executes the Command
     *
     * @throws ErrorException
     *
     * @return bool
     */
    public function execute(): bool
    {
        if (!$envFile = $this->options['file']) {
            throw new ErrorException('There should be an env file for saving the env vars.');
        }

        return $this->getFilesystem()->put($envFile, $this->getEnvVarsAsString());
    }

    /**
     * Returns an empty whitelist and a default file.
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'file' => '.env',
            'whitelist' => [],
        ];
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return trim(sprintf(
            '[Env] Creates the env file %s/%s',
            getcwd(),
            $this->options['file'] ?? ''
        ));
    }

    /**
     * Iterates through the env vars and creates a dotenv-compatible string out of them.
     *
     * @return string
     */
    private function getEnvVarsAsString(): string
    {
        $contents = '';
        $whitelist = is_array($this->options['whitelist']) ? $this->options['whitelist'] : [];

        foreach ($_ENV as $key => $value) {
            if (!$whitelist || in_array($key, $whitelist)) {
                $contents .= "{$key}={$value}\n";
            }
        }

        return $contents;
    }

    /**
     * Returns the used file system.
     *
     * @return FilesystemInterface
     */
    private function getFilesystem(): FilesystemInterface
    {
        if (!$this->filesystem) {
            $this->setFilesystem(new Filesystem(new Local(getcwd())));
        }

        return $this->filesystem;
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'env/create-env-file';
    }

    /**
     * Sets the used file system.
     *
     * @param FilesystemInterface $filesystem
     *
     * @return $this
     */
    public function setFilesystem(FilesystemInterface $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }
}
