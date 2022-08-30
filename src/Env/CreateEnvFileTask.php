<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Env;

use BestIt\Mage\Tasks\Misc\LocalFilesystemAwareTrait;
use BestIt\Mage\Tasks\Shopware\AbstractTask;
use Mage\Task\Exception\ErrorException;
use function getcwd;
use function in_array;
use function is_numeric;
use function str_replace;

/**
 * Creates a local environment file (relative to the working directory) for the actual php environment.
 *
 * @package BestIt\Mage\Tasks\Env
 */
class CreateEnvFileTask extends AbstractTask
{
    use LocalFilesystemAwareTrait;

    /**
     * Executes the Command
     *
     * @throws ErrorException
     *
     * @return bool
     */
    public function execute(): bool
    {
        if (!$envFile = $this->options['file']) {
            throw new ErrorException('There should be an env file for saving the env vars.');
        }

        return $this->getFilesystem()->put($envFile, $this->getEnvVarsAsString());
    }

    /**
     * Returns an empty whitelist and a default file.
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'file' => '.env',
            'whitelist' => [],
        ];
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return trim(sprintf(
            '[Env] Creates the env file %s/%s',
            getcwd(),
            $this->options['file'] ?? '',
        ));
    }

    /**
     * Iterates through the env vars and creates a dotenv-compatible string out of them.
     *
     * @return string
     */
    private function getEnvVarsAsString(): string
    {
        $contents = '';
        $whitelist = is_array($this->options['whitelist']) ? $this->options['whitelist'] : [];

        if ($whitelist && !$_ENV) {
            throw new ErrorException(
                'There should be env vars. We suggest changing the php config "variables_order" to "EGPCS".',
            );
        }

        foreach ($_ENV as $key => $value) {
            if (!$whitelist || in_array($key, $whitelist)) {
                if ((!is_numeric($value)) && (!in_array($value, ['false', 'true']))) {
                    $value = '"' . str_replace('"', '\\"', $value) . '"';
                }

                $contents .= "{$key}={$value}\n";
            }
        }

        return $contents;
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'env/create-env-file';
    }
}
