<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

/**
 * Helps you accessing a local file system.
 *
 * @package BestIt\Mage\Tasks\Misc
 */
trait LocalFilesystemAwareTrait
{
    /**
     * This file system is registered to the current working directory.
     *
     * This property is filled with lazy loading by the getter.
     *
     * @var FilesystemInterface|null
     */
    protected ?FilesystemInterface $filesystem = null;

    /**
     * Returns the used file system.
     *
     * @return FilesystemInterface
     */
    protected function getFilesystem(): FilesystemInterface
    {
        if (!$this->filesystem) {
            $this->setFilesystem(new Filesystem(new Local($this->getFilesystemPath())));
        }

        return $this->filesystem;
    }

    /**
     * Returns the path to the relevant folder which is edited.
     *
     * @return string
     */
    protected function getFilesystemPath(): string
    {
        return getcwd();
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
