<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\BuiltIn\Deploy\Tar\CopyTask as MageCopyTask;
use Mage\Task\Exception\SkipException;

/**
 * Class CopyTask
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class CopyTask extends MageCopyTask
{
    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Misc] Skip default copy tar command.';
    }

    /**
     * Executes the task.
     *
     * @throws SkipException
     *
     * @return void
     */
    public function execute(): void
    {
        throw new SkipException();
    }
}
