<?php

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class UpdateLegacyPluginsTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
class UpdateLegacyPluginsTask extends AbstractUpdatePluginsTask
{
    /**
     * Temporary marker for every plugin namespace.
     *
     * @var string
     */
    protected $pluginDir;

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'shopware/update-legacy-plugins';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Shopware] Update legacy plugins.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute()
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

    /**
     * @param string|null $source
     *
     * @return bool
     */
    protected function syncNamespacesForSource($source = null)
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

    /**
     * @param string $pluginDir
     *
     * @return UpdateLegacyPluginsTask
     */
    protected function setPluginDir($pluginDir)
    {
        $this->pluginDir = $pluginDir;

        return $this;
    }

    /**
     * @return string
     */
    protected function getPluginDir()
    {
        return $this->pluginDir;
    }

    /**
     * @return bool
     */
    protected function shouldSyncSourcesFolders()
    {
        return isset($this->options['sync_sources_folders']) ? $this->options['sync_sources_folders'] : false;
    }

    /**
     * @param string $default
     *
     * @return string
     */
    protected function getPluginRootPath($default)
    {
        return isset($this->options['plugin_root_path']) ? $this->options['plugin_root_path'] : $default;
    }
}
