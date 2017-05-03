<?php declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;

/**
 * Class ThemeSynchronizeTask
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class ThemeSynchronizeTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/theme-synchronize';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Synchronizes theme configurations with the database.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = sprintf(
            'php %s/bin/console sw:theme:synchronize',
            $this->runtime->getEnvOption('from', '.')
        );

        $process = $this->runtime->runCommand($cmd);
        return $process->isSuccessful();
    }
}
