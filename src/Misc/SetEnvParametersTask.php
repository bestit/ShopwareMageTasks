<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use BestIt\Mage\Tasks\Env\SetEnvParametersTask as OriginalTask;

/**
 * Fallback class for BC.
 *
 * @package BestIt\Mage\Tasks\Misc
 * @deprecated since version 0.7.0. To be removed in 1.0.0.
 */
class SetEnvParametersTask extends OriginalTask
{
    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'misc/set-env-parameters';
    }
}
