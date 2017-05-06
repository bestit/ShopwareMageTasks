<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;

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
        $cmd = 'php ./scripts/ApplyDeltas.php --tablesuffix="bestit" --migrationpath="./sql/" --shoppath="." --mode=update';

        $process = $this->runtime->runRemoteCommand($cmd, true);
        return $process->isSuccessful();
    }
}
