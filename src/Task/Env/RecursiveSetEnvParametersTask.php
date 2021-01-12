<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Env;

use Mage\Task\Exception\ErrorException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Extends SetEnvParametersTask by a recursive directory iteration to edit all dist files with env parameters.
 *
 * @author Johannes Borgwardt <johannes.borgwardt@bestit-online.de>
 * @package BestIt\Mage\Task\Env
 */
class RecursiveSetEnvParametersTask extends SetEnvParametersTask
{
    /**
     * File extension string for dist files.
     */
    public const DIST_FILE_EXTENSION = '.dist';

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'env/recursive-set-env-parameters';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Env] Set parameters from env variables in dist files recursively.';
    }

    /**
     * Executes the Command
     *
     * @throws ErrorException
     *
     * @return bool
     */
    public function execute(): bool
    {
        $fileName = $this->options['fileName'];

        $directory = __DIR__;
        if (isset($this->options['directory']) && !empty($this->options['directory'])) {
            $directory = $this->options['directory'];
        }

        $dirIterator = new RecursiveDirectoryIterator($directory);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::SELF_FIRST);
        $files = [];
        foreach ($iterator as $file) {
            assert($file instanceof SplFileInfo);
            if ($file->isFile() && $file->isReadable()) {
                if (strpos($file->getFilename(), $fileName) !== false) {
                    $files[] = $file->getPathname();
                }
            }
        }

        if (count($files) === 0) {
            return false;
        }

        foreach ($files as $file) {
            $this->options['file'] = $file;
            $this->options['target'] = str_replace(self::DIST_FILE_EXTENSION, '', $file);

            // Delete existing checked in file
            if ((bool) $this->options['deleteTargets'] === true) {
                unlink($this->options['target']);
            }

            // Call parent to create file from dist.
            if (parent::execute() === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        $defaults = parent::getDefaults();
        $defaults['deleteTargets'] = false;

        return $defaults;
    }
}
