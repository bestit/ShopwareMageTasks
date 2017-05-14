<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncLegacyLocalPluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncLegacyLocalPluginsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'deploy/legacy-local-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying legacy local plugins.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute()
    {
        return $this->sync();
    }

    /**
     * @return string
     */
    protected function getSource()
    {
        return parent::getSource(). '/legacy_plugins/Local/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Local/';
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders()
    {
        return isset($this->options['sync_sources_folders']) ? $this->options['sync_sources_folders'] : false;
    }
}
