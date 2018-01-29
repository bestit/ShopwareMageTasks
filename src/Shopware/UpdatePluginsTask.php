<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class UpdatePluginsTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
class UpdatePluginsTask extends AbstractUpdatePluginsTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'shopware/update-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Shopware] Update plugins.';
    }

    /**
     * @return string
     */
    protected function getPluginDir()
    {
        return $this->runtime->getEnvOption('from', '.') . '/plugins/';
    }
}
