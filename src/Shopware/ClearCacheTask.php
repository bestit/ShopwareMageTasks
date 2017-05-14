<?php

namespace BestIt\Mage\Tasks\Shopware;

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
    public function getName()
    {
        return 'shopware/clear-cache';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Shopware] Clear the shopware cache.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute()
    {
        $cmd = sprintf('bash ./var/cache/clear_cache.sh');

        $process = $this->runtime->runRemoteCommand($cmd, true);
        return $process->isSuccessful();
    }
}
