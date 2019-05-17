<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Shopware;

/**
 * Class ApplyMigrationsTask
 *
 * @package BestIt\Mage\Tasks\Shopware
 */
class ApplyMigrationsTask extends AbstractTask
{
    /**
     * The default directory where the migrations are stored.
     *
     * @internal
     * @var string
     */
    const DEFAULT_MIGRATION_DIR = 'sql';

    /**
     * The default directory where the php script is found.
     *
     * @internal
     * @var string
     */
    const DEFAULT_SCRIPT_DIR = 'scripts';

    /**
     * The default path to the shop.
     *
     * @internal
     * @var string
     */
    const DEFAULT_SHOP_PATH = '.';

    /**
     * The default table suffix for the migrations table.
     *
     * @internal
     * @var string
     */
    const DEFAULT_TABLE_SUFFIX = 'bestit';

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'shopware/migrate';
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription()
    {
        return '[Shopware] Apply migrations.';
    }

    /**
     * Executes the command.
     *
     * @return bool
     */
    public function execute()
    {
        $cmd = sprintf(
            '%s ./%s/ApplyDeltas.php --tablesuffix="%s" --migrationpath="./%s/" --shoppath="%s" --mode=update',
            $this->getPathToPhpExecutable(),
            $this->getScriptDirName(),
            $this->getTableSuffix(),
            $this->getMigrationDirName(),
            $this->getPathToShopware()
        );

        $process = $this->runtime->runRemoteCommand($cmd, true, $this->options['timeout']);

        return $process->isSuccessful();
    }

    /**
     * Returns the default for this mage task.
     *
     * @return array
     */
    public function getDefaults()
    {
        return [
            'timeout' => 120
        ];
    }

    /**
     * Returns the configured path to shopware or the default.
     *
     * @return string
     */
    private function getPathToShopware(): string
    {
        return $this->options['shop_path'] ?? self::DEFAULT_SHOP_PATH;
    }

    /**
     * Returns the configured table suffix or returns the default.
     *
     * @return string
     */
    private function getTableSuffix(): string
    {
        return isset($this->options['table_suffix']) ? $this->options['table_suffix'] : self::DEFAULT_TABLE_SUFFIX;
    }

    /**
     * Returns the path to the migration directoy or returns the default.
     *
     * @return string
     */
    private function getMigrationDirName(): string
    {
        return isset($this->options['migration_dir']) ? $this->options['migration_dir'] : self::DEFAULT_MIGRATION_DIR;
    }

    /**
     * Returns the directory of the php executable or returns the default.
     *
     * @return string
     */
    private function getScriptDirName(): string
    {
        return isset($this->options['script_dir']) ? $this->options['script_dir'] : self::DEFAULT_SCRIPT_DIR;
    }
}
