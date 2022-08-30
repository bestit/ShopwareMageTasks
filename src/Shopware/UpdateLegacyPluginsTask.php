<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

class UpdateLegacyPluginsTask extends AbstractUpdatePluginsTask
{
    protected string $pluginDir;

    public function getName(): string
    {
        return 'shopware/update-legacy-plugins';
    }

    public function getDescription(): string
    {
        return '[Shopware] Update legacy plugins.';
    }

    public function execute(): bool
    {
        $sources = ['Community', 'Local'];

        if ($this->shouldSyncSourcesFolders()) {
            foreach ($sources as $source) {
                if (!$this->syncNamespacesForSource($source)) {
                    return false;
                }
            }
        } else {
            return $this->syncNamespacesForSource(null);
        }

        return true;
    }

    protected function syncNamespacesForSource($source = null): bool
    {
        $namespaces = ['Backend', 'Core', 'Frontend'];
        $rootPath = $this->runtime->getEnvOption('from', '.');
        $pathToPluginDir = $this->getPluginRootPath("{$rootPath}/legacy_plugins/");

        if ($source !== null) {
            $pathToPluginDir .= $source;
        }

        foreach ($namespaces as $namespace) {
            $this->setPluginDir("{$pathToPluginDir}/{$namespace}/");

            if (!parent::execute()) {
                return false;
            }
        }

        return true;
    }

    protected function setPluginDir($pluginDir): self
    {
        $this->pluginDir = $pluginDir;

        return $this;
    }

    protected function getPluginDir(): string
    {
        return $this->pluginDir;
    }

    protected function shouldSyncSourcesFolders(): bool
    {
        return isset($this->options['sync_sources_folders']) ? $this->options['sync_sources_folders'] : false;
    }

    protected function getPluginRootPath(string $default): string
    {
        return isset($this->options['plugin_root_path']) ? $this->options['plugin_root_path'] : $default;
    }
}
