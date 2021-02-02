<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Env;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class SetEnvParametersTask
 *
 * @package BestIt\Mage\Task\Env
 */
class SetEnvParametersTask extends AbstractTask
{
    /**
     * Executes the Command
     *
     * @throws ErrorException when there is no config file
     *
     * @return bool
     */
    public function execute(): bool
    {
        $pathToConfig = $this->options['file'];
        $pathToTarget = isset($this->options['target']) ? $this->options['target'] : $pathToConfig;
        $prefix = $this->options['prefix'];
        $encodeForXml = $this->options['encodeForXml'];
        $wrapper = $this->options['placeholderWrapper'];

        if (!is_file($pathToConfig)) {
            throw new ErrorException("File not found: {$pathToConfig}");
        }

        $configContent = file_get_contents($pathToConfig);

        $envVars = array_replace_recursive($_ENV, $_SERVER);

        foreach ($envVars as $key => $value) {
            /*
             * Skip all values that are not a string (because we cannot use str_replace on those values).
             * And also skip any keys which do not start with the given prefix.
             * */
            if (!is_string($value) || (!empty($prefix) && strpos($key, $prefix) !== 0)) {
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

            $configContent = str_replace("{$wrapper}{$placeholder}{$wrapper}", $value, $configContent);
        }

        $res = file_put_contents($pathToTarget, $configContent);

        return $res !== false;
    }

    /**
     * Gets the default values
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'prefix' => 'ENV_',
            'encodeForXml' => false,
            'placeholderWrapper' => '%',
        ];
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Env] Set parameters from env variables.';
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'env/set-env-parameters';
    }
}
