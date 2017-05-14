<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncLibrariesTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncLibrariesTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'deploy/libraries';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying libraries.';
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
        return parent::getSource(). '/libraries/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/engine/Library/';
    }

    /**
     * @return string
     */
    protected function getRsyncFlags()
    {
        return '-rvz';
    }
}
