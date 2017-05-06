<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncScriptsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncScriptsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/scripts';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying script files.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->sync();
    }

    /**
     * @return string
     */
    protected function getSource(): string
    {
        return parent::getSource() . '/scripts/remote/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/scripts';
    }
}
