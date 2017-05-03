<?php declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;
use Symfony\Component\Process\Process;

/**
 * Class AdminUsersTask
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class AdminUsersTask extends AbstractTask
{
    /**
     * Yml file name of option to put all needed admin users into.
     */
    const ENV_OPTION_ADMINS = 'admins';

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/create-admins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Create admin users in the backend.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     * @throws \Mage\Task\Exception\ErrorException
     */
    public function execute(): bool
    {
        // get options from .yml
        $options = (array) $this->runtime->getEnvOption(self::ENV_OPTION_ADMINS, []);

        // check for existing parameters
        if (!array_key_exists(self::ENV_OPTION_ADMINS, $options)) {
            throw new ErrorException(
                'Parameter "' . self::ENV_OPTION_ADMINS . '" is required.'
            );
        }

        // create admin user for every option
        foreach ($options as $option) {
            // prepare user data
            $userData = [
                '--email=' . $option . '@bestit-online.de',
                '--username=' . $option,
                '--name=best it | ' . $option,
                '--locale=en_GB',
                '--password=' . md5($option)
            ];

            $user = implode(' ', $userData);

            // prepare command
            $cmd = sprintf(
                'php ./bin/console sw:admin:create %s',
                $user . '--no-interaction'
            );

            /** @var Process $process */
            $process = $this->runtime->runRemoteCommand($cmd, true);

            // only return if command exited with error
            if ($process->isSuccessful() === false) {
                return false;
            }
        }

        return true;
    }
}
