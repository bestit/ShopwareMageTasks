<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Release;

use Mage\Task\BuiltIn\Deploy\Release\PrepareTask as MagePrepareTask;
use Mage\Task\Exception\SkipException;

/**
 * Class PrepareTask
 *
 * @package BestIt\Mage\Task\Release
 */
class PrepareTask extends MagePrepareTask
{
    /**
     * Executes the task.
     *
     * @throws SkipException
     *
     * @return bool
     */
    public function execute(): bool
    {
        throw new SkipException('Skip default prepare task.');
    }
}
