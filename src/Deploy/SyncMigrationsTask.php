<?php

namespace BestIt\Mage\Tasks\Deploy;

/**
 * Class SyncMigrationsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncMigrationsTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/migrations';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying migrations.';
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
        $source = $this->options['src'] ?? 'sql';

        return parent::getSource(). $source;
    }
}
