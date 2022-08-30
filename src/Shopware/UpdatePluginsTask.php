<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

class UpdatePluginsTask extends AbstractUpdatePluginsTask
{
    public function getName(): string
    {
        return 'shopware/update-plugins';
    }

    public function getDescription(): string
    {
        return '[Shopware] Update plugins.';
    }

    protected function getPluginDir(): string
    {
        return $this->runtime->getEnvOption('from', '.') . '/plugins/';
    }
}
