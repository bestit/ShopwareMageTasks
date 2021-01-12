<?php

declare(strict_types=1);

use BestIt\Mage\Task\Deploy\DeployTask;
use BestIt\Mage\Task\Deploy\Tar\PrepareSubfolderTask;
use BestIt\Mage\Task\Env\CreateEnvFileTask;
use BestIt\Mage\Task\Env\RecursiveSetEnvParametersTask;
use BestIt\Mage\Task\Env\SetEnvParametersTask;
use BestIt\Mage\Task\Misc\CopyTask;
use BestIt\Mage\Task\Misc\DenyRobotsTxtTask;
use BestIt\Mage\Task\Misc\SubComposerInstallTask;
use BestIt\Mage\Task\Opcode\BuildCleanerTask;
use BestIt\Mage\Task\Release\SwPrepareTask;
use BestIt\Mage\Task\Shopware\AbstractTask;
use BestIt\Mage\Task\Shopware\AbstractUpdatePluginsTask;
use BestIt\Mage\Task\Shopware\ApplyMigrationsTask;
use BestIt\Mage\Task\Shopware\CommandTask;
use BestIt\Mage\Task\Shopware\UpdateLegacyPluginsTask;
use BestIt\Mage\Task\Shopware\UpdatePluginsTask;

/**
 * Mage use the class names to load custom tasks. As part of further development we change the directory and namespace
 * structure. To prevent breaking changes we create aliases for the old class names.
 */

$taskList = [
    PrepareSubfolderTask::class => [
        '\BestIt\Mage\Tasks\Deploy\Tar\PrepareSubfolderTask',
    ],
    DeployTask::class => [
        '\BestIt\Mage\Tasks\Deploy\DeployTask',
    ],
    CreateEnvFileTask::class => [
        '\BestIt\Mage\Tasks\Env\CreateEnvFileTask',
    ],
    RecursiveSetEnvParametersTask::class => [
        '\BestIt\Mage\Tasks\Env\RecursiveSetEnvParametersTask',
    ],
    SetEnvParametersTask::class => [
        '\BestIt\Mage\Tasks\Env\SetEnvParametersTask',
    ],
    CopyTask::class => [
        '\BestIt\Mage\Tasks\Misc\CopyTask',
    ],
    DenyRobotsTxtTask::class => [
        '\BestIt\Mage\Tasks\Misc\DenyRobotsTxtTask',
    ],
    \BestIt\Mage\Task\Misc\SetEnvParametersTask::class => [
        '\BestIt\Mage\Tasks\Misc\SetEnvParametersTask',
    ],
    SubComposerInstallTask::class => [
        '\BestIt\Mage\Tasks\Misc\SubComposerInstallTask',
    ],
    BuildCleanerTask::class => [
        '\BestIt\Mage\Tasks\Opcode\BuildCleanerTask',
    ],
    SwPrepareTask::class => [
        'BestIt\Mage\Tasks\Release\SwPrepareTask',
    ],
    AbstractTask::class => [
        '\BestIt\Mage\Tasks\Shopware\AbstractTask',
    ],
    AbstractUpdatePluginsTask::class => [
        '\BestIt\Mage\Tasks\Shopware\AbstractUpdatePluginsTask',
    ],
    ApplyMigrationsTask::class => [
        '\BestIt\Mage\Tasks\Shopware\ApplyMigrationsTask',
    ],
    CommandTask::class => [
        '\BestIt\Mage\Tasks\Shopware\CommandTask',
    ],
    UpdateLegacyPluginsTask::class => [
        '\BestIt\Mage\Tasks\Shopware\UpdateLegacyPluginsTask',
    ],
    UpdatePluginsTask::class => [
        '\BestIt\Mage\Tasks\Shopware\UpdatePluginsTask',
    ],
];

foreach ($taskList as $task => $aliasList) {
    foreach ($aliasList as $alias) {
        if (!class_exists($alias)) {
            class_alias($task, $alias);
        }
    }
}
