<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncLegacyCommunityPluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncLegacyCommunityPluginsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'deploy/legacy-community-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying legacy community plugins.';
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
        return parent::getSource(). '/legacy_plugins/Community/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Community/';
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders()
    {
        return isset($this->options['sync_sources_folders']) ? $this->options['sync_sources_folders'] : false;
    }

    /**
     * @return string
     */
    protected function getRsyncFlags()
    {
        return '-rvz --no-o';
    }
}
