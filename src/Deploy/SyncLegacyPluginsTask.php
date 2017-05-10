<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncLegacyPluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncLegacyPluginsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/legacy-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying legacy plugins.';
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
        return parent::getSource(). '/legacy_plugins/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        $path = '/engine/Shopware/Plugins/';

        if (!$this->shouldSyncSourcesFolders()) {
            $path .= 'Local/';
        }

        return parent::getTarget() . $path;
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders(): bool
    {
        return $this->options['sync_sources_folders'] ?? false;
    }
}
