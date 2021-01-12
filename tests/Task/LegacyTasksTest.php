<?php

declare(strict_types=1);

namespace BestIt\Mage\Task;

use PHPUnit\Framework\TestCase;

/**
 * Test that legacy class names works.
 *
 * @package BestIt\Mage\Task
 */
class LegacyTasksTest extends TestCase
{
    /**
     * Get legacy class names.
     *
     * @return array
     */
    public function getLegacyClassNames(): array
    {
        return [
            ['\BestIt\Mage\Tasks\Deploy\Tar\PrepareSubfolderTask',],
            ['\BestIt\Mage\Tasks\Deploy\DeployTask',],
            ['\BestIt\Mage\Tasks\Env\CreateEnvFileTask'],
            ['\BestIt\Mage\Tasks\Env\RecursiveSetEnvParametersTask',],
            ['\BestIt\Mage\Tasks\Env\SetEnvParametersTask'],
            ['\BestIt\Mage\Tasks\Misc\CopyTask',],
            ['\BestIt\Mage\Tasks\Misc\DenyRobotsTxtTask',],
            ['\BestIt\Mage\Tasks\Misc\SetEnvParametersTask'],
            ['\BestIt\Mage\Tasks\Misc\SubComposerInstallTask',],
            ['\BestIt\Mage\Tasks\Opcode\BuildCleanerTask',],
            ['BestIt\Mage\Tasks\Release\SwPrepareTask',],
            ['\BestIt\Mage\Tasks\Shopware\AbstractTask',],
            ['\BestIt\Mage\Tasks\Shopware\AbstractUpdatePluginsTask',],
            ['\BestIt\Mage\Tasks\Shopware\ApplyMigrationsTask',],
            ['\BestIt\Mage\Tasks\Shopware\CommandTask'],
            ['\BestIt\Mage\Tasks\Shopware\UpdateLegacyPluginsTask'],
            ['\BestIt\Mage\Tasks\Shopware\UpdatePluginsTask',],
        ];
    }

    /**
     * Test that legacy class name works.
     *
     * @dataProvider getLegacyClassNames
     *
     * @param string $legacyClassName
     *
     * @return void
     */
    public function testLegacyClassName(string $legacyClassName): void
    {
        self::assertTrue(class_exists($legacyClassName));
    }
}
