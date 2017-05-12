<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class UpdatePluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class UpdatePluginsTask extends AbstractUpdatePluginsTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/update-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Update plugins.';
    }

    /**
     * @return string
     */
    protected function getPluginDir(): string
    {
        return $this->runtime->getEnvOption('from', '.') . '/plugins/';
    }
}
