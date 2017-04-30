<?php

namespace BestIt\Mage\Tasks\Deploy;

class SyncThemesTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/themes';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying themes.';
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
        return parent::getSource(). '/themes/';
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return parent::getTarget() . '/themes/Frontend/';
    }

    /**
     * @return string
     */
    protected function getRsyncFlags(): string
    {
        return '-rvz';
    }
}
