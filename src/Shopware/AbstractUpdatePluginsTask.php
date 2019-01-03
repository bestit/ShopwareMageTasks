<?php

namespace BestIt\Mage\Tasks\Shopware;

use DirectoryIterator;
use Symfony\Component\Process\Process;

/**
 * Class AbstractUpdatePluginsTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
abstract class AbstractUpdatePluginsTask extends AbstractTask
{
    /**
     * Executes the task.
     *
     * @return bool
     */
    public function execute()
    {
        return $this->updateAllInDir($this->getPluginDir());
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return [
            'timeout' => 240
        ];
    }

    /**
     * Update all plugins in the given directory.
     *
     * @param string $directory
     * @return bool
     */
    protected function updateAllInDir($directory)
    {
        if (!$this->refreshPluginList()) {
            echo 'Could not refresh plugin list';
            return false;
        }

        $dirIterator = new DirectoryIterator($directory);
        $allRemoteCommands = [];

        foreach ($dirIterator as $file) {
            if ($file->isDot() || !$file->isDir()) {
                continue;
            }

            $cmd = sprintf(
                '%s %s sw:plugin:update %s',
                $this->getPathToPhpExecutable(),
                $this->getPathToConsoleScript(),
                $file->getFilename()
            );

            if ($this->shouldUseSingleRemoteCommand()) {
                $allRemoteCommands[] = $cmd;
            } else {
                $process = $this->runtime->runRemoteCommand($cmd, true, $this->options['timeout']);

                if (!$this->isSuccessful($process)) {
                    return false;
                }
            }
        }

        if ($this->shouldUseSingleRemoteCommand() && $allRemoteCommands !== []) {
            $process = $this->runtime->runRemoteCommand(
                implode(' && ', $allRemoteCommands),
                true,
                $this->options['timeout']
            );

            if (!$this->isSuccessful($process)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Refresh the plugin list.
     *
     * @return bool
     */
    protected function refreshPluginList()
    {
        $cmd = sprintf(
            '%s %s sw:plugin:refresh',
            $this->getPathToPhpExecutable(),
            $this->getPathToConsoleScript()
        );
        $process = $this->runtime->runRemoteCommand($cmd, true);

        if (!$process->isSuccessful()) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the update should be executed as single remote command for all plugins.
     *
     * @return bool
     */
    protected function shouldUseSingleRemoteCommand()
    {
        return isset($this->options['single_remote_command']) ? (bool) $this->options['single_remote_command'] : false;
    }

    /**
     * Check if the process was successful.
     *
     * @param Process $process
     * @return bool
     */
    protected function isSuccessful(Process $process)
    {
        /**
         * We need to check if the output contains 'is up to date' because shopware
         * returns a non-zero exit code if the plugin is already installed so our process
         * would be marked as failed even though it did not actually fail.
         */
        if (!$process->isSuccessful() && strpos($process->getOutput(), 'is up to date') === false) {
            echo $process->getOutput();
            echo $process->getErrorOutput();

            return false;
        }

        return true;
    }

    /**
     * Get directory of the plugins.
     *
     * @return string
     */
    abstract protected function getPluginDir();
}
