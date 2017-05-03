<?php declare(strict_types=1);

namespace BestIt\Mage\Tasks\Quality;

use Mage\Task\AbstractTask;
use Symfony\Component\Process\Process;

/**
 * Class PhpCodeSnifferTask
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package BestIt\Mage\Tasks\Quality
 */
class PhpCodeSnifferTask extends AbstractTask
{
    /**
     * Paths to check with php codesniffer in Shopware
     */
    const PATHS_TO_CHECK = [
        './custom',
        './engine/Shopware/Plugins/Local'
    ];

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'quality/php-codesniffer';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Quality] Static code analysis by php codesniffer.';
    }

    /**
     * Executes the command.
     * @return bool
     */
    public function execute(): bool
    {
        $paths = self::PATHS_TO_CHECK;

        foreach ($paths as $path) {
            $process = $this->checkPath($path);
            if ($process->isSuccessful() === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Helper method to check every path by php codesniffer
     * @param $path
     * @return Process
     */
    private function checkPath($path)
    {
        $switches = '--report=summary --standard=PSR2';
        $cmd = './vendor/bin/phpcs ' . $switches . $path;

        return $this->runtime->runLocalCommand($cmd);
    }
}
