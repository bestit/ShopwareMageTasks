<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

use Mage\Task\AbstractTask as MageAbstractTask;

/**
 * Class AbstractTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
abstract class AbstractTask extends MageAbstractTask
{
    /**
     * Returns the console script path if configured, otherwise ./bin/console as default value.
     *
     * @return string The configured or default console script path.
     */
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

    /**
     * Gets the path where the php executable is located
     *
     * @return string
     */
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
}
