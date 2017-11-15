<?php

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class SetEnvParametersTask
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class SetEnvParametersTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'misc/set-env-parameters';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Misc] Set parameters from env variables.';
    }

    /**
     * Executes the Command
     *
     * @return bool
     *
     * @throws ErrorException
     */
    public function execute()
    {
        $pathToConfig = $this->options['file'];

        if (!is_file($pathToConfig)) {
            throw new ErrorException("File not found: {$pathToConfig}");
        }

        $configContent = file_get_contents($pathToConfig);

        $envVars = array_merge_recursive($_ENV, $_SERVER);

        foreach ($envVars as $key => $value) {
            $configContent = str_replace("%{$key}%", $value, $configContent);
        }

        $res = file_put_contents($pathToConfig, $configContent);

        return $res !== false;
    }
}
