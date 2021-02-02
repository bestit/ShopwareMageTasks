<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

/**
 * Class UpdatePluginsTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
class UpdatePluginsTask extends AbstractUpdatePluginsTask
{
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
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/update-plugins';
    }

    /**
     * Get the plugin directory
     *
     * @return string
     */
    protected function getPluginDir(): string
    {
        return $this->runtime->getEnvOption('from', '.') . '/plugins/';
    }
}
