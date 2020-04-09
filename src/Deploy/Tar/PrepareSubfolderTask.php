<?php

namespace BestIt\Mage\Tasks\Deploy\Tar;

use Mage\Task\Exception\ErrorException;
use Symfony\Component\Process\Process;
use Mage\Task\BuiltIn\Deploy\Tar\PrepareTask;

/**
 * Tar Task - Create temporal Tar of files in a specific subfolder
 *
 * Replaces the original PrepareTask
 *
 * @class PrepareSubfolderTask
 *
 * @package BestIt\Mage\Tasks\Deploy\Tar
 */
class PrepareSubfolderTask extends PrepareTask
{
    public function getName()
    {
        return 'deploy/tar/prepare';
    }

    public function getDescription()
    {
        return '[Deploy] Preparing Tar file for subfolder';
    }

    public function execute()
    {
        if (!$this->runtime->getEnvOption('releases', false)) {
            throw new ErrorException('This task is only available with releases enabled', 40);
        }

        $tarLocal = $this->runtime->getTempFile();
        $this->runtime->setVar('tar_local', $tarLocal);

        $excludes = $this->getExcludes();
        $tarPath = $this->runtime->getEnvOption('tar_create_path', 'tar');
        $flags = $this->runtime->getEnvOption('tar_create', 'cfzp');
        $from = $this->runtime->getEnvOption('from', './');

        // create tar of a subfolder
        $cmdTar = sprintf('%s %s %s %s -C %s .', $tarPath, $flags, $tarLocal, $excludes, $from);

        /** @var Process $process */
        $process = $this->runtime->runLocalCommand($cmdTar, 300);

        return $process->isSuccessful();
    }
}
