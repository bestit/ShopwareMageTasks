<?php

namespace BestIt\Mage\Tasks\Release;

use Mage\Task\AbstractTask;

/**
 * Class PrepareTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Release
 */
class SwPrepareTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'prepare/sw-structure';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Prepare] Creating releases directory and copying contents of current into it.';
    }

    /**
     * Executes the task.
     *
     * @return bool
     */
    public function execute()
    {
        $hostPath = rtrim($this->runtime->getEnvOption('host_path'), '/');
        $releaseId = $this->runtime->getReleaseId();

        $cmdMakeDir = sprintf(
            'cp -rp %s/current/ %s/releases/%s',
            $hostPath,
            $hostPath,
            $releaseId
        );

        $process = $this->runtime->runRemoteCommand($cmdMakeDir, false, $this->options['timeout']);
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
}
