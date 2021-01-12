<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Shopware;

/**
 * Class UpdateLegacyPluginsTask
 *
 * @package BestIt\Mage\Task\Shopware
 */
class UpdateLegacyPluginsTask extends AbstractUpdatePluginsTask
{
    /**
     * Temporary marker for every plugin namespace.
     *
     * @var string
     */
    protected string $pluginDir;

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $result = true;

        $sources = ['Community', 'Local'];

        if ($this->shouldSyncSourcesFolders()) {
            foreach ($sources as $source) {
                if (!$this->syncNamespacesForSource($source)) {
                    $result = false;
                }
            }
        } else {
            $result = $this->syncNamespacesForSource(null);
        }

        return $result;
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Shopware] Update legacy plugins.';
    }

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
     * Gets the directory of the plugins
     *
     * @return string
     */
    protected function getPluginDir(): string
    {
        return $this->pluginDir;
    }

    /**
     * Gets the root path of the plugins
     *
     * @param string $default
     *
     * @return string
     */
    protected function getPluginRootPath($default): string
    {
        return isset($this->options['plugin_root_path']) ? $this->options['plugin_root_path'] : $default;
    }

    /**
     * Sets the directory of the plugins
     *
     * @param string $pluginDir
     *
     * @return UpdateLegacyPluginsTask
     */
    protected function setPluginDir($pluginDir): UpdateLegacyPluginsTask
    {
        $this->pluginDir = $pluginDir;

        return $this;
    }

    /**
     * Returns whether source folder should be synced
     *
     * @return bool
     */
    protected function shouldSyncSourcesFolders(): bool
    {
        return isset($this->options['sync_sources_folders']) ? $this->options['sync_sources_folders'] : false;
    }

    /**
     * Returns whether namespace should be synced
     *
     * @param string|null $source
     *
     * @return bool
     */
    protected function syncNamespacesForSource($source = null): bool
    {
        $result = true;

        $namespaces = ['Backend', 'Core', 'Frontend'];
        $rootPath = $this->runtime->getEnvOption('from', '.');
        $pathToPluginDir = $this->getPluginRootPath("{$rootPath}/legacy_plugins/");

        if ($source !== null) {
            $pathToPluginDir .= $source;
        }

        foreach ($namespaces as $namespace) {
            $this->setPluginDir("{$pathToPluginDir}/{$namespace}/");

            if (!parent::execute()) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
