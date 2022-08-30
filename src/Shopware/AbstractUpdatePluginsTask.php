<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

use DirectoryIterator;
use Symfony\Component\Process\Process;

abstract class AbstractUpdatePluginsTask extends AbstractTask
{
    public function execute(): bool
    {
        return $this->updateAllInDir($this->getPluginDir());
    }

    public function getDefaults(): array
    {
        return [
            'timeout' => 240,
        ];
    }

    protected function updateAllInDir($directory): bool
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
                $file->getFilename(),
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

        if ($this->shouldUseSingleRemoteCommand() && $allRemoteCommands) {
            $process = $this->runtime->runRemoteCommand(
                implode(' && ', $allRemoteCommands),
                true,
                $this->options['timeout'],
            );

            if (!$this->isSuccessful($process)) {
                return false;
            }
        }

        return true;
    }

    protected function refreshPluginList(): bool
    {
        if ($this->shouldRefreshPluginList()) {
            $cmd = sprintf(
                '%s %s sw:plugin:refresh',
                $this->getPathToPhpExecutable(),
                $this->getPathToConsoleScript(),
            );
            $process = $this->runtime->runRemoteCommand($cmd, true);

            if (!$process->isSuccessful()) {
                return false;
            }
        }

        return true;
    }

    protected function shouldUseSingleRemoteCommand(): bool
    {
        return isset($this->options['single_remote_command']) ? (bool) $this->options['single_remote_command'] : false;
    }

    protected function shouldRefreshPluginList(): bool
    {
        return isset($this->options['plugin_refresh']) ? (bool) $this->options['plugin_refresh'] : true;
    }

    protected function isSuccessful(Process $process): bool
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

    abstract protected function getPluginDir(): string;
}
