<?php declare(strict_types=1);

namespace BestIt\Mage\Tasks\Quality;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class VendorBinTask
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package BestIt\Mage\Tasks\Quality
 */
class VendorBinTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'vendor/bin';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        try {
            return sprintf(
                '[Quality] Run vendor bin command "%s" with flags: "%s"',
                $this->getCommand(),
                $this->getFlags()
            );
        } catch (ErrorException $exception) {
            return '[Quality] Execute command [missing parameters]';
        }
    }

    /**
     * Executes the command.
     * @return bool
     */
    public function execute()
    {
        $rootDir = $this->runtime->getEnvOption('from', '.');

        try {
            $cmd = sprintf(
                'php ' . $rootDir . '/vendor/bin/%s %s %s',
                $this->getCommand(),
                $this->getDirectories(),
                $this->getFlags()
            );
        } catch (ErrorException $exception) {
            return false;
        }

        $process = $this->runtime->runLocalCommand($cmd);

        if (!$process->isSuccessful()) {
            return false;
        }

        return true;
    }

    /**
     * Get the vendor bin command to be run.
     *
     * @return string
     * @throws ErrorException
     */
    protected function getCommand()
    {
        if (!isset($this->options['cmd'])) {
            throw new ErrorException('Command argument missing');
        }
        return (string) $this->options['cmd'];
    }

    /**
     * Get directories to check for files.
     *
     * @return string
     * @throws ErrorException
     */
    protected function getDirectories()
    {
        if (!isset($this->options['dir'])) {
            throw new ErrorException('Second command argument missing');
        }
        return (string) $this->options['dir'];
    }

    /**
     * Get the flags specified for the command.
     *
     * @return string
     */
    protected function getFlags()
    {
        return isset($this->options['flags']) ? $this->options['flags'] : '';
    }
}
