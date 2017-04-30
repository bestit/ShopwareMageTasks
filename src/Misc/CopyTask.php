<?php

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\BuiltIn\Deploy\Tar\CopyTask as MageCopyTask;
use Mage\Task\Exception\SkipException;

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
     */
    public function execute()
    {
        throw new SkipException();
    }
}
