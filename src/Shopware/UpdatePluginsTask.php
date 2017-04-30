<?php

namespace BestIt\Mage\Tasks\Shopware;

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
        return '[Shopware] Install/Update plugins.';
    }

    /**
     * @return string
     */
    protected function getPluginDir(): string
    {
        return $this->runtime->getEnvOption('from', '.') . '/plugins/';
    }
}
