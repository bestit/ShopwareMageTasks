<?php

namespace BestIt\Mage\Tasks\Deploy;

use Mage\Task\AbstractTask;

/**
 * Class ReplacePlaceHoldersTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Deploy
 */
class ReplacePlaceHoldersTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'prepare/deploy';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Prepare] Replacing placeholders with actual values.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        $environment = $this->runtime->getEnvironment();
        $upperEnvironment = strtoupper($environment);

        $pathToRoot = $this->runtime->getEnvOption('from', '.');
        $pathToConfig = "{$pathToRoot}/configs/config_{$environment}.php";
        $pathToDevScript = "{$pathToRoot}/scripts/remote/dev_environment.sh";

        $parameters = ['DB_HOST', 'DB_NAME', 'DB_PASS', 'DB_PORT', 'DB_USER'];

        /* Replace config placeholder values. */
        foreach ($parameters as $parameter) {
            $cmd = sprintf(
                'sed -i "s/%s/%s/g" %s',
                "{$upperEnvironment}_{$parameter}", // Search
                getenv("{$upperEnvironment}_{$parameter}"), // Replace
                $pathToConfig // File path
            );

            $process = $this->runtime->runLocalCommand($cmd);

            if (!$process->isSuccessful()) {
                return false;
            }
        }

        /* Replace dev_environment.sh placeholder values. */
        foreach ($parameters as $parameter) {
            $cmd = sprintf(
                'sed -i "s/%s/%s/g" %s',
                "VAGRANT_{$parameter}", // Search
                getenv("VAGRANT_{$parameter}"), // Replace
                $pathToDevScript // File path
            );

            $process = $this->runtime->runLocalCommand($cmd);

            if (!$process->isSuccessful()) {
                return false;
            }
        }

        return true;
    }
}
