<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncPluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncPluginsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying plugins.';
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
        return parent::getSource(). '/plugins';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/custom/';
    }
}
