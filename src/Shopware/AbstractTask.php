<?php

namespace BestIt\Mage\Tasks\Shopware;

use Mage\Task\AbstractTask as MageAbstractTask;

/**
 * Class AbstractTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
abstract class AbstractTask extends MageAbstractTask
{
    /**
     * @return string
     */
    protected function getPathToPhpExecutable()
    {
        $phpExecutable = 'php';

        $configPhpExecutable = $this->runtime->getConfigOption('php_executable');
        $environmentPhpExecutable = $this->runtime->getEnvOption('php_executable');

        if ($configPhpExecutable !== null) {
            $phpExecutable = $configPhpExecutable;
        }

        if ($environmentPhpExecutable !== null) {
            $phpExecutable = $environmentPhpExecutable;
        }

        return $phpExecutable;
    }
}
