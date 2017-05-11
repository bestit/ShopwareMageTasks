<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class ApplyMigrationsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class ApplyMigrationsTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/migrate';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Apply migrations.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = sprintf(
            '%s ./scripts/ApplyDeltas.php --tablesuffix="%s" --migrationpath="./%s/" --shoppath="." --mode=update',
            $this->getPathToPhpExecutable(),
            $this->getTableSuffix(),
            $this->getMigrationDirName()
        );

        $process = $this->runtime->runRemoteCommand($cmd, true);
        return $process->isSuccessful();
    }

    /**
     * @return string
     */
    protected function getTableSuffix(): string
    {
        return $this->options['table_suffix'] ?? 'bestit';
    }

    /**
     * @return string
     */
    protected function getMigrationDirName(): string
    {
        return $this->options['migration_dir'] ?? 'sql';
    }
}
