<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Release;

use Mage\Task\AbstractTask;

/**
 * Class PrepareTask
 *
 * @package BestIt\Mage\Task\Release
 */
class SwPrepareTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'prepare/sw-structure';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Prepare] Creating releases directory and copying contents of current into it.';
    }

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
            'cp -rp %s/current/ %s/releases/%s',
            $hostPath,
            $hostPath,
            $releaseId,
        );

        $process = $this->runtime->runRemoteCommand($cmdMakeDir, false, $this->options['timeout']);
        return $process->isSuccessful();
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'timeout' => 120,
        ];
    }
}
