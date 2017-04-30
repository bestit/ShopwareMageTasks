<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;

class ThemeCacheWarmupTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/theme-cache-warmup';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Generate shopware theme cache.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = sprintf(
            'php %s/bin/console sw:theme:cache:generate',
            $this->runtime->getEnvOption('from', '.')
        );

        $process = $this->runtime->runCommand($cmd);
        return $process->isSuccessful();
    }
}
