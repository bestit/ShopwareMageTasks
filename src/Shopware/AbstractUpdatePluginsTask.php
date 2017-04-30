<?php

namespace BestIt\Mage\Tasks\Shopware;

use DirectoryIterator;
use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

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
     * Install/Update all plugins in the given directory.
     *
     * @param string $directory
     * @return bool
     */
    protected function updateAllInDir(string $directory): bool
    {
        if (!$this->refreshPluginList()) {
            echo 'Could not refresh plugin list';
            return false;
        }

        $dirIterator = new DirectoryIterator($directory);

        foreach ($dirIterator as $file) {
            if ($file->isDot() || !$file->isDir()) {
                continue;
            }

            $cmd = sprintf(
                'php ./bin/console sw:plugin:install %s --activate && php ./bin/console sw:plugin:update %s',
                $file->getFilename(),
                $file->getFilename()
            );

            $process = $this->runtime->runRemoteCommand($cmd, true, 240);

            if (!$this->isSuccessful($process)) {
                echo $process->getOutput();
                echo $process->getErrorOutput();

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
    protected function refreshPluginList(): bool
    {
        $cmd = sprintf('php ./bin/console sw:plugin:refresh');
        $process = $this->runtime->runRemoteCommand($cmd, true);

        if (!$process->isSuccessful()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the process was successful.
     *
     * @param Process $process
     * @return bool
     */
    protected function isSuccessful(Process $process): bool
    {
        /**
         * We need to check if the output contains 'is already installed' because shopware
         * returns a non-zero exit code if the plugin is already installed so our process
         * would be marked as failed even though it did not actually fail.
         */
        if (!$process->isSuccessful() && strpos($process->getOutput(), 'is already installed.') === false) {
            echo $process->getOutput();
            return false;
        }

        return true;
    }

    /**
     * Get directory of the plugins.
     *
     * @return string
     */
    abstract protected function getPluginDir(): string;
}
