<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncThemesTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncThemesTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'deploy/themes';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Deploy] Copying themes.';
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
        return parent::getSource(). '/themes/';
    }

    /**
     * @return string
     */
    protected function getTarget()
    {
        return parent::getTarget() . '/themes/Frontend/';
    }

    /**
     * @return string
     */
    protected function getRsyncFlags()
    {
        return '-rvz';
    }
}
