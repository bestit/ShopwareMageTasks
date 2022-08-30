<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\Exception\ErrorException;
use Mage\Task\Exception\SkipException;
use Mage\Task\ExecuteOnRollbackInterface;

class CommandTask extends AbstractTask implements ExecuteOnRollbackInterface
{
    public function getName(): string
    {
        return 'shopware/command';
    }

    public function getDescription(): string
    {
        try {
            return sprintf('[Shopware] Execute command "%s" with flags: "%s"', $this->getCommand(), $this->options['flags']);
        } catch (ErrorException $exception) {
            return '[Shopware] Execute command [missing parameters]';
        }
    }

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

    protected function getCommand(): string
    {
        if (!isset($this->options['cmd'])) {
            throw new ErrorException('Command argument missing');
        }

        return (string) $this->options['cmd'];
    }

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
}
