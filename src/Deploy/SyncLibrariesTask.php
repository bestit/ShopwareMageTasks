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
    public function getName(): string
    {
        return 'deploy/libraries';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying libraries.';
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
        return parent::getSource(). '/libraries/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/engine/Library/';
    }

    /**
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        return '-rvz';
    }
}
