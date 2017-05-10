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
    public function getName(): string
    {
        return 'deploy/legacy-community-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying legacy community plugins.';
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
        return parent::getSource(). '/legacy_plugins/Community/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Community/';
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders(): bool
    {
        return $this->options['sync_sources_folders'] ?? false;
    }

    /**
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        return '-rvz --no-o';
    }
}
