<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Misc;

use Mage\Task\BuiltIn\Deploy\Tar\CopyTask as MageCopyTask;
use Mage\Task\Exception\SkipException;

/**
 * Class CopyTask
 *
 * @package BestIt\Mage\Task\Misc
 */
class CopyTask extends MageCopyTask
{
    /**
     * Executes the task.
     *
     * @throws SkipException when method is invoked
     *
     * @return void
     */
    public function execute(): void
    {
        throw new SkipException();
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Misc] Skip default copy tar command.';
    }
}
