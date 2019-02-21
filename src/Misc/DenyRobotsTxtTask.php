<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\AbstractTask;

/**
 * Creates a robot.txt which denies all.
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class DenyRobotsTxtTask extends AbstractTask
{
    use LocalFilesystemAwareTrait;

    /**
     * Executes the Command
     *
     * @return bool
     */
    public function execute(): bool
    {
        return $this->getFilesystem()->put(
            'robots.txt',
            "User-agent: *\nDisallow: /\n"
        );
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Misc] Create robots.txt which denies all';
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'misc/deny-robots-txt';
    }
}
