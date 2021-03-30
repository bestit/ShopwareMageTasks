<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\AbstractTask;

/**
 * Creates a robot.txt which allowed all.
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class AllowRobotsTxtTask extends AbstractTask
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
            ($this->options['folder'] ?? '') . '/robots.txt',
            "User-agent: *\n" .
                    "Allow: /\n" .
                    "Disallow: /account/\n" .
                    "Disallow: /checkout/\n" .
                    "Disallow: /widgets/\n" .
                    "Disallow: /navigation/\n" .
                    "Disallow: /bundles/\n" .
                    "Disallow: /_proxy/\n"
        );
    }

    /**
     * Get a short Description of the Task
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '[Misc] Create robots.txt which alowed all';
    }

    /**
     * Get the Name/Code of the Task
     *
     * @return string
     */
    public function getName(): string
    {
        return 'misc/allow-robots-txt';
    }
}
