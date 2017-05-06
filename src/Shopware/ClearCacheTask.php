<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;

/**
 * Class ClearCacheTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
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
        $cmd = sprintf('bash ./var/cache/clear_cache.sh');

        $process = $this->runtime->runRemoteCommand($cmd, true);
        return $process->isSuccessful();
    }
}
