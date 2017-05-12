<?php

namespace BestIt\Mage\Tasks\Deploy;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class SyncFileTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncFileTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/file';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        try {
            return sprintf('[Deploy] Syncing file "%s" to directory "%s"', $this->getSource(), $this->getTarget());
        } catch (ErrorException $e) {
            return '[Deploy] Syncing file [missing parameters]';
        }
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        $sshConfig = $this->runtime->getSSHConfig();

        $command = sprintf(
            'rsync -e "ssh -p %d %s" %s %s %s@%s:%s',
            $sshConfig['port'],
            $sshConfig['flags'],
            $this->getRsyncFlags(),
            $this->getSource(),
            $this->runtime->getEnvOption('user', $this->runtime->getCurrentUser()),
            $this->runtime->getWorkingHost(),
            $this->getTarget()
        );

        $process = $this->runtime->runLocalCommand($command);

        if (!$process->isSuccessful()) {
            echo $process->getErrorOutput();
            echo $process->getOutput();

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        return $this->options['rsync_flags'] ?? '-rvz';
    }

    /**
     * Get source file.
     *
     * @return string
     * @throws ErrorException
     */
    protected function getSource(): string
    {
        if (!isset($this->options['src'])) {
            throw new ErrorException('No source file specified.');
        }

        $source = $this->runtime->getEnvOption('from', '.');

        return "{$source}/{$this->options['src']}";
    }

    /**
     * Get target directory.
     *
     * @return string
     * @throws ErrorException
     */
    protected function getTarget(): string
    {
        if (!isset($this->options['target'])) {
            throw new ErrorException('No target directory specified.');
        }

        $targetDir = rtrim($this->getHostPath(), '/');
        $currentReleaseId = $this->runtime->getReleaseId();

        if ($currentReleaseId !== null) {
            $targetDir = sprintf('%s/releases/%s', $targetDir, $currentReleaseId);
        }

        return "{$targetDir}/{$this->options['target']}";
    }

    /**
     * @return string
     */
    protected function getHostPath(): string
    {
        return $this->runtime->getEnvOption('host_path');
    }
}
