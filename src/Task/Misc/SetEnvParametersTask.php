<?php

declare(strict_types=1);

namespace BestIt\Mage\Task\Misc;

use BestIt\Mage\Task\Env\SetEnvParametersTask as OriginalTask;

/**
 * Fallback class for BC.
 *
 * @deprecated since version 0.7.0. To be removed in 1.0.0.
 * @package BestIt\Mage\Task\Misc
 */
class SetEnvParametersTask extends OriginalTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'misc/set-env-parameters';
    }
}
