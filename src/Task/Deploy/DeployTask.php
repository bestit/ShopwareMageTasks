<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Deploy;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class DeployTask
 *
 * @package BestIt\Mage\Task\Deploy
 */
class DeployTask extends AbstractTask
{
    /**
     * Executes the Command
     *
     * @throws ErrorException when either from or to option is not given
     *
     * @return bool
     */
    public function execute(): bool
    {
        if (!isset($this->options['from'], $this->options['to'])) {
            throw new ErrorException();
        }

        $sshConfig = $this->runtime->getSSHConfig();

        $command = sprintf(
            'rsync -e "ssh -p %d %s" %s %s %s@%s:%s',
            $sshConfig['port'],
            $sshConfig['flags'],
            $this->getRsyncFlags(),
            $this->options['from'],
            $this->runtime->getEnvOption('user', $this->runtime->getCurrentUser()),
            $this->runtime->getWorkingHost(),
            $this->getTarget(),
        );

        $process = $this->runtime->runLocalCommand($command, $this->options['timeout']);
        return $process->isSuccessful();
    }

    /**
     * Gets the default values
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'strict' => true,
            'timeout' => 120,
        ];
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return "[Deploy] {$this->options['from']} to {$this->options['to']}";
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy';
    }

    /**
     * Gets the flag for the rsync command
     *
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        $result = '-rvz';

        if (isset($this->options['flags'])) {
            $result = $this->options['flags'];
        }

        if (isset($this->options['strict']) && $this->options['strict']) {
            $result = '-rvz --delete --no-o';
        }

        return $result;
    }

    /**
     * Gets the target folder where the files should be deployed to
     *
     * @return string
     */
    protected function getTarget(): string
    {
        $targetDir = rtrim($this->runtime->getEnvOption('host_path'), '/');
        $currentReleaseId = $this->runtime->getReleaseId();

        if ($currentReleaseId !== null) {
            $targetDir = sprintf('%s/releases/%s', $targetDir, $currentReleaseId);
        }

        return "{$targetDir}/{$this->options['to']}";
    }
}
