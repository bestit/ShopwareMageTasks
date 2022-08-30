<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask as MageAbstractTask;

abstract class AbstractTask extends MageAbstractTask
{
    protected function getPathToPhpExecutable(): string
    {
        $phpExecutable = 'php';

        $configPhpExecutable = $this->runtime->getConfigOption('php_executable');
        $environmentPhpExecutable = $this->runtime->getEnvOption('php_executable');

        if ($configPhpExecutable !== null) {
            $phpExecutable = $configPhpExecutable;
        }

        if ($environmentPhpExecutable !== null) {
            $phpExecutable = $environmentPhpExecutable;
        }

        return $phpExecutable;
    }

    protected function getPathToConsoleScript(): string
    {
        $consoleScriptPath = './bin/console';

        $environmentConsoleScriptPath = $this->runtime->getEnvOption('console_script_path');

        if ($environmentConsoleScriptPath !== null) {
            $consoleScriptPath = $environmentConsoleScriptPath;
        }

        if ($this->runtime->getReleaseId() !== null) {
            $consoleScriptPath = str_replace('%release%', $this->runtime->getReleaseId(), $consoleScriptPath);
        }

        return $consoleScriptPath;
    }
}
