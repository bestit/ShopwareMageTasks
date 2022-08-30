<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Release;

use Mage\Task\AbstractTask;

class SwPrepareTask extends AbstractTask
{
    public function getName(): string
    {
        return 'prepare/sw-structure';
    }

    public function getDescription(): string
    {
        return '[Prepare] Creating releases directory and copying contents of current into it.';
    }

    public function execute(): bool
    {
        $hostPath = rtrim($this->runtime->getEnvOption('host_path'), '/');
        $releaseId = $this->runtime->getReleaseId();

        $cmdMakeDir = sprintf(
            'cp -rp %s/current/ %s/releases/%s',
            $hostPath,
            $hostPath,
            $releaseId,
        );

        $process = $this->runtime->runRemoteCommand($cmdMakeDir, false, $this->options['timeout']);
        return $process->isSuccessful();
    }

    public function getDefaults(): array
    {
        return [
            'timeout' => 120,
        ];
    }
}
