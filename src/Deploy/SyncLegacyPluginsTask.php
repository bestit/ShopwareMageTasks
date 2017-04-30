<?php

namespace BestIt\Mage\Tasks\Deploy;

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

    protected function getTarget(): string
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Local/';
    }
}
