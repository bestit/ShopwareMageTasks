<?php

namespace BestIt\Mage\Tasks\Release;

use Mage\Task\BuiltIn\Deploy\Release\PrepareTask as MagePrepareTask;

/**
 * Class PrepareTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Release
 */
class PrepareTask extends MagePrepareTask
{
    /**
     * Executes the task.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $hostPath = rtrim($this->runtime->getEnvOption('host_path'), '/');
        $releaseId = $this->runtime->getReleaseId();

        $cmdMakeDir = sprintf(
            'mkdir -p %s/releases/%s && cp -rp %s/current/. %s/releases/%s',
            $hostPath,
            $releaseId,
            $hostPath,
            $hostPath,
            $releaseId
        );

        $process = $this->runtime->runRemoteCommand($cmdMakeDir, false);
        return $process->isSuccessful();
    }
}
