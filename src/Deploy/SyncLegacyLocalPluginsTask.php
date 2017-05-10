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
    public function getName(): string
    {
        return 'deploy/legacy-local-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying legacy local plugins.';
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
        return parent::getSource(). '/legacy_plugins/Local/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Local/';
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders(): bool
    {
        return $this->options['sync_sources_folders'] ?? false;
    }
}
