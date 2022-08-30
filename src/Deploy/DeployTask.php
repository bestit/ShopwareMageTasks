<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Deploy;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

class DeployTask extends AbstractTask
{
    public function getName(): string
    {
        return 'deploy';
    }

    public function getDescription(): string
    {
        return "[Deploy] {$this->options['from']} to {$this->options['to']}";
    }

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

    public function getDefaults(): array
    {
        return [
            'strict' => true,
            'timeout' => 120,
        ];
    }

    protected function getRsyncFlags(): string
    {
        if (isset($this->options['flags'])) {
            return $this->options['flags'];
        }

        if (isset($this->options['strict']) && $this->options['strict']) {
            return '-rvz --delete --no-o';
        }

        return '-rvz';
    }

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
