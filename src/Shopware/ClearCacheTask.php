<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;

class ClearCacheTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/clear-cache';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Clear the shopware cache.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = sprintf(
            'bash %s/var/cache/clear_cache.sh',
            $this->runtime->getEnvOption('from', '.')
        );

        $process = $this->runtime->runCommand($cmd);
        return $process->isSuccessful();
    }
}
