<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask;
use Mage\Task\Exception\ErrorException;

/**
 * Class CommandTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class CommandTask extends AbstractTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/command';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        try {
            return sprintf('[Shopware] Execute command "%s" with flags: "%s"', $this->getCommand(), $this->getFlags());
        } catch (ErrorException $exception) {
            return '[Shopware] Execute command [missing parameters]';
        }
    }

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        $cmd = sprintf('php ./bin/console %s %s', $this->getCommand(), $this->getFlags());
        $process = $this->runtime->runRemoteCommand($cmd, true);

        return $process->isSuccessful();
    }

    /**
     * Get the SW command to be run.
     *
     * @return string
     * @throws ErrorException
     */
    protected function getCommand(): string
    {
        if (!isset($this->options['cmd'])) {
            throw new ErrorException('Command argument missing');
        }

        return (string) $this->options['cmd'];
    }

    /**
     * Get the flags specified for the command.
     *
     * @return string
     */
    protected function getFlags(): string
    {
        return $this->options['flags'] ?? '';
    }
}
