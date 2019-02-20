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
     * @throws ErrorException
     *
     * @return bool
     */
    public function execute()
    {
        $pathToConfig = $this->options['file'];
        $pathToTarget = isset($this->options['target']) ? $this->options['target'] : $pathToConfig;
        $prefix = $this->options['prefix'];
        $encodeForXml = $this->options['encodeForXml'];

        if (!is_file($pathToConfig)) {
            throw new ErrorException("File not found: {$pathToConfig}");
        }

        $configContent = file_get_contents($pathToConfig);

        $envVars = array_merge_recursive($_ENV, $_SERVER);

        foreach ($envVars as $key => $value) {
            /*
             * Skip all values that are not a string (because we cannot use str_replace on those values).
             * And also skip any keys which do not start with the given prefix.
             * */
            if (!is_string($value) || strpos($key, $prefix) !== 0) {
                continue;
            }

            if ($encodeForXml) {
                $value = htmlspecialchars($value, ENT_QUOTES);
            }

            /*
             * Remove the environment prefix from the key, so that it matches up with the
             * placeholders in the given file (the prefix should not be included in the placeholder)
             */
            $placeholder = str_replace($prefix, '', $key);

            $configContent = str_replace("%{$placeholder}%", $value, $configContent);
        }

        $res = file_put_contents($pathToTarget, $configContent);

        return $res !== false;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return [
            'prefix' => 'ENV_',
            'encodeForXml' => false
        ];
    }
}
