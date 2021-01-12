<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

use Mage\Task\Exception\ErrorException;
use Mage\Task\Exception\SkipException;
use Mage\Task\ExecuteOnRollbackInterface;

/**
 * Class CommandTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
class CommandTask extends AbstractTask implements ExecuteOnRollbackInterface
{
    /**
     * Executes the Command
     *
     * @throws SkipException when execOnRollback option is set to false
     *
     * @return bool
     */
    public function execute(): bool
    {
        if (!$this->options['execOnRollback'] && $this->runtime->inRollback()) {
            throw new SkipException("Task skipped because 'execOnRollback' is set to 'false'.");
        }

        $cmd = sprintf(
            '%s %s %s %s',
            $this->getPathToPhpExecutable(),
            $this->getPathToConsoleScript(),
            $this->getCommand(),
            $this->options['flags'],
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
     * @throws ErrorException when no cmd option is given
     *
     * @return string
     */
    protected function getCommand(): string
    {
        if (!isset($this->options['cmd'])) {
            throw new ErrorException('Command argument missing');
        }

        return (string) $this->options['cmd'];
    }

    /**
     * Gets the default values
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'flags' => '',
            // Ignores the command return value and always returns true
            // The usage of this option should be considered carefully because with this options all values will
            // be ignored. Exceptions and other errors wont be detected.
            'ignoreReturnValue' => false,
            'execOnRollback' => false,
            'timeout' => 120,
        ];
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        $result = '';

        try {
            $result = sprintf(
                '[Shopware] Execute command "%s" with flags: "%s"',
                $this->getCommand(),
                $this->options['flags'],
            );
        } catch (ErrorException $exception) {
            $result = '[Shopware] Execute command [missing parameters]';
        }

        return $result;
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/command';
    }
}
