<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

use DirectoryIterator;
use Symfony\Component\Process\Process;

/**
 * Class AbstractUpdatePluginsTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
abstract class AbstractUpdatePluginsTask extends AbstractTask
{
    /**
     * Executes the task.
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->updateAllInDir($this->getPluginDir());
    }

    /**
     * Gets the default values
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'timeout' => 240,
        ];
    }

    /**
     * Get directory of the plugins.
     *
     * @return string
     */
    abstract protected function getPluginDir(): string;

    /**
     * Check if the process was successful.
     *
     * @param Process $process
     *
     * @return bool
     */
    protected function isSuccessful(Process $process): bool
    {
        $result = true;

        /**
         * We need to check if the output contains 'is up to date' because shopware
         * returns a non-zero exit code if the plugin is already installed so our process
         * would be marked as failed even though it did not actually fail.
         */
        if (!$process->isSuccessful() && strpos($process->getOutput(), 'is up to date') === false) {
            echo $process->getOutput();
            echo $process->getErrorOutput();

            $result = false;
        }

        return $result;
    }

    /**
     * Refresh the plugin list.
     *
     * @return bool
     */
    protected function refreshPluginList(): bool
    {
        $result = true;

        if ($this->shouldRefreshPluginList()) {
            $cmd = sprintf(
                '%s %s sw:plugin:refresh',
                $this->getPathToPhpExecutable(),
                $this->getPathToConsoleScript(),
            );
            $process = $this->runtime->runRemoteCommand($cmd, true);

            if (!$process->isSuccessful()) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Checks if the plugin list should be refreshed.
     *
     * @return bool
     */
    protected function shouldRefreshPluginList(): bool
    {
        return isset($this->options['plugin_refresh']) ? (bool) $this->options['plugin_refresh'] : true;
    }

    /**
     * Checks if the update should be executed as single remote command for all plugins.
     *
     * @return bool
     */
    protected function shouldUseSingleRemoteCommand(): bool
    {
        return isset($this->options['single_remote_command']) ? (bool) $this->options['single_remote_command'] : false;
    }

    /**
     * Update all plugins in the given directory.
     *
     * @param string $directory
     *
     * @return bool
     */
    protected function updateAllInDir($directory): bool
    {
        $result = true;

        if (!$this->refreshPluginList()) {
            echo 'Could not refresh plugin list';
            $result = false;
        }
        if ($result) {
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
                    $file->getFilename(),
                );

                if ($this->shouldUseSingleRemoteCommand()) {
                    $allRemoteCommands[] = $cmd;
                } else {
                    $process = $this->runtime->runRemoteCommand($cmd, true, $this->options['timeout']);

                    if (!$this->isSuccessful($process)) {
                        $result = false;
                    }
                }
            }

            if ($result) {
                if ($this->shouldUseSingleRemoteCommand() && $allRemoteCommands) {
                    $process = $this->runtime->runRemoteCommand(
                        implode(' && ', $allRemoteCommands),
                        true,
                        $this->options['timeout'],
                    );

                    if (!$this->isSuccessful($process)) {
                        $result = false;
                    }
                }
            }
        }

        return $result;
    }
}
