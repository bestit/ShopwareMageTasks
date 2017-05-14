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
    public function getName()
    {
        return 'deploy/legacy-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying legacy plugins.';
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
        return parent::getSource(). '/legacy_plugins/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/engine/Shopware/Plugins/Local/';
    }
}
