<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class UpdateLegacyPluginsTask
 *
 * @author Ahmad El-Bardan <ahmad.el-bardan@bestit-online.de>
 * @package BestIt\Mage\Tasks\Shopware
 */
class UpdateLegacyPluginsTask extends AbstractUpdatePluginsTask
{
    /** @var string $pluginDir */
    protected $pluginDir;

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'shopware/update-legacy-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Install/Update legacy plugins.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $sources = ['Community', 'Default', 'Local'];

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

    /**
     * @param string|null $source
     * @return bool
     */
    protected function syncNamespacesForSource($source = null): bool
    {
        $namespaces = ['Backend', 'Core', 'Frontend'];
        $rootPath = $this->runtime->getEnvOption('from', '.');
        $pathToPluginDir = "{$rootPath}/legacy_plugins/";

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

    /**
     * @param string $pluginDir
     * @return UpdateLegacyPluginsTask
     */
    protected function setPluginDir(string $pluginDir): UpdateLegacyPluginsTask
    {
        $this->pluginDir = $pluginDir;

        return $this;
    }

    /**
     * @return string
     */
    protected function getPluginDir(): string
    {
        return $this->pluginDir;
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders(): bool
    {
        return $this->options['sync_sources_folders'] ?? false;
    }
}
