<?php

namespace BestIt\Mage\Tasks\Release;

use Mage\Task\BuiltIn\Deploy\Release\PrepareTask as MagePrepareTask;
use Mage\Task\Exception\SkipException;

/**
 * Class PrepareTask
 *
 * @package BestIt\Mage\Tasks\Release
 */
class PrepareTask extends MagePrepareTask
{
    /**
     * Executes the task.
     *
     * @throws SkipException
     *
     * @return bool
     */
    public function execute()
    {
        throw new SkipException('Skip default prepare task.');
    }
}
