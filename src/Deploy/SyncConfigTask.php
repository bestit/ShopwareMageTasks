<?php

namespace BestIt\Mage\Tasks\Deploy;

use Mage\Task\Exception\ErrorException;

/**
 * Class SyncConfigTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class SyncConfigTask extends AbstractSyncTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'deploy/config';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Deploy] Copying config.php.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     * @throws ErrorException
     */
    public function execute(): bool
    {
        return $this->sync();
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return parent::getTarget() . '/config.php';
    }

    public function getSource(): string
    {
        return parent::getSource() . "/configs/config_{$this->runtime->getEnvironment()}.php";
    }
}
