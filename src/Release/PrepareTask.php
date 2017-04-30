<?php

namespace BestIt\Mage\Tasks\Release;

use Mage\Task\BuiltIn\Deploy\Release\PrepareTask as MagePrepareTask;

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

        $cmdMakeDir = sprintf(
            'mkdir -p %s/releases/%s && cp -rp %s/current/. %s/releases/%s',
            $hostPath,
            $this->runtime->getReleaseId(),
            $hostPath,
            $hostPath,
            $this->runtime->getReleaseId()
        );

        $process = $this->runtime->runRemoteCommand($cmdMakeDir, false);
        return $process->isSuccessful();
    }
}
