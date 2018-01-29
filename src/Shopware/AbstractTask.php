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
        return
            ($this->runtime->getConfigOption('php_executable') !== null)
            ? $this->runtime->getConfigOption('php_executable')
            : 'php'
        ;
    }
}
