<?php

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\BuiltIn\Deploy\Tar\CopyTask as MageCopyTask;
use Mage\Task\Exception\SkipException;

/**
 * Class CopyTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Misc
 */
class CopyTask extends MageCopyTask
{
    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
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
