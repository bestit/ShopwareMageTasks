<?php

namespace BestIt\Mage\Tasks\Deploy;

use Mage\Task\BuiltIn\Deploy\RsyncTask;
use Mage\Task\Exception\SkipException;

abstract class AbstractSyncTask extends RsyncTask
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '[Overwrite] Skipping default sync step...';
    }

    /**
     * Executes the Command
     *
     * @throws SkipException
     */
    public function execute()
    {
        throw new SkipException();
    }

    /**
     * Sync the given directories/files.
     *
     * @return bool
     */
    protected function sync(): bool
    {
        $flags = $this->getRsyncFlags();
        $sshConfig = $this->runtime->getSSHConfig();
        $user = $this->runtime->getEnvOption('user', $this->runtime->getCurrentUser());
        $host = $this->runtime->getWorkingHost();
        $targetDir = $this->getTarget();

        $excludes = $this->getExcludes();
        $from = $this->getSource();

        $command = sprintf(
            'rsync -e "ssh -p %d %s" %s %s %s %s@%s:%s',
            $sshConfig['port'], $sshConfig['flags'], $flags, $excludes, $from, $user, $host, $targetDir
        );

        $process = $this->runtime->runLocalCommand($command, 600);

        if (!$process->isSuccessful()) {
            echo $process->getErrorOutput();
            echo $process->getOutput();

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getSshConfig(): array
    {
        return $this->runtime->getSSHConfig();
    }

    /**
     * @return string
     */
    protected function getCurrentUser(): string
    {
        return $this->runtime->getEnvOption('user', $this->runtime->getCurrentUser());
    }

    /**
     * @return string|null
     */
    protected function getWorkingHost()
    {
        return $this->runtime->getWorkingHost();
    }

    /**
     * @return string
     */
    protected function getHostPath(): string
    {
        return $this->runtime->getEnvOption('host_path');
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        $targetDir = rtrim($this->getHostPath(), '/');
        $currentReleaseId = $this->runtime->getReleaseId();

        if ($currentReleaseId !== null) {
            $targetDir = sprintf('%s/releases/%s', $targetDir, $currentReleaseId);
        }

        return $targetDir;
    }

    /**
     * @return string
     */
    protected function getSource(): string
    {
        return $this->runtime->getEnvOption('from', './');
    }

    /**
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        return $this->runtime->getEnvOption('rsync', '-avz');
    }
}
