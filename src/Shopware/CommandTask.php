<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\Exception\ErrorException;
use Mage\Task\Exception\SkipException;
use Mage\Task\ExecuteOnRollbackInterface;

/**
 * Class CommandTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
class CommandTask extends AbstractTask implements ExecuteOnRollbackInterface
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'shopware/command';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        try {
            return sprintf('[Shopware] Execute command "%s" with flags: "%s"', $this->getCommand(), $this->options['flags']);
        } catch (ErrorException $exception) {
            return '[Shopware] Execute command [missing parameters]';
        }
    }

    /**
     * Executes the Command
     *
     * @return bool
     * @throws SkipException
     */
    public function execute()
    {
        if (!$this->options['execOnRollback'] && $this->runtime->inRollback()) {
            throw new SkipException("Task skipped because 'execOnRollback' is set to 'false'.");
        }

        $cmd = sprintf(
            '%s %s %s %s',
            $this->getPathToPhpExecutable(),
            $this->getPathToConsoleScript(),
            $this->getCommand(),
            $this->options['flags']
        );
        $process = $this->runtime->runRemoteCommand($cmd, true, $this->options['timeout']);

        $returnValue = $process->isSuccessful();
        if ($this->options['ignoreReturnValue']) {
            $returnValue = true;
        }

        return $returnValue;
    }

    /**
     * Get the SW command to be run.
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
     * @return array
     */
    public function getDefaults()
    {
        return [
            'flags' => '',
            // Ignores the command return value and always returns true
            // The usage of this option should be considered carefully because with this options all values will
            // be ignored. Exceptions and other errors wont be detected.
            'ignoreReturnValue' => false,
            'execOnRollback' => false,
            'timeout' => 120
        ];
    }
}
