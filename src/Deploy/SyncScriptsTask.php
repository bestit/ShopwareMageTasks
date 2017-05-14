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
    public function getName()
    {
        return 'deploy/scripts';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying script files.';
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
        return parent::getSource() . '/scripts/remote/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/scripts';
    }
}
