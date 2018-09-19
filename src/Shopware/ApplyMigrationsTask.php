<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class ApplyMigrationsTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
class ApplyMigrationsTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'shopware/migrate';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Shopware] Apply migrations.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute()
    {
        $cmd = sprintf(
            '%s ./%s/ApplyDeltas.php --tablesuffix="%s" --migrationpath="./%s/" --shoppath="." --mode=update',
            $this->getPathToPhpExecutable(),
            $this->getScriptDirName(),
            $this->getTableSuffix(),
            $this->getMigrationDirName()
        );

        $process = $this->runtime->runRemoteCommand($cmd, true, $this->options['timeout']);
        return $process->isSuccessful();
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return [
            'timeout' => 120
        ];
    }

    /**
     * @return string
     */
    protected function getTableSuffix()
    {
        return isset($this->options['table_suffix']) ? $this->options['table_suffix'] : 'bestit';
    }

    /**
     * @return string
     */
    protected function getMigrationDirName()
    {
        return isset($this->options['migration_dir']) ? $this->options['migration_dir'] : 'sql';
    }

    /**
     * @return string
     */
    protected function getScriptDirName()
    {
        return isset($this->options['script_dir']) ? $this->options['script_dir'] : 'scripts';
    }
}
