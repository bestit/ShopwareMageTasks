<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Opcode;

use Mage\Task\AbstractTask;
use function implode;

/**
 * Calls the cleaner and removes it afterwards.
 *
 * @package BestIt\Mage\Tasks\Opcode
 */
class CleanReset extends AbstractTask
{
    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        foreach ($this->getUrlsOption() as $url) {
            $this->runtime->runCommand('curl -k -X GET ' . $url . '/apc_clear.php');
        }

        $deleteSuccess = true;

        if (!@$this->options['without_delete']) {
            $deleteSuccess = $this->runtime
                ->runCommand('rm -rf ' . ($this->options['doc_root'] ?? '.') . '/apc_clear.php')
                ->isSuccessful();
        }

        return $deleteSuccess;
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[opcode] Calls the cleaner of ' . implode(', ', $this->getUrlsOption()) . '.';
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'opcode/clean-reset';
    }

    /**
     * Returns the urls from the option.
     *
     * @return array
     */
    private function getUrlsOption(): array
    {
        return $this->options['urls'] ?? [];
    }
}
