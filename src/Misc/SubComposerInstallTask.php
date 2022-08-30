<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Misc;

use Mage\Task\BuiltIn\Composer\InstallTask;
use function array_merge;
use function glob;
use function implode;
use function is_array;
use function str_replace;

/**
 * Iterates thru a list of globs (php glob()-compatible) and composer-installs for every found folder.
 *
 * @package BestIt\Mage\Tasks\Misc
 */
class SubComposerInstallTask extends InstallTask
{
    /**
     * Iterates thru a list of globs (php glob()-compatible) and composer-installs for every found folder.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $options = $this->getOptions();
        $globs = $options['globs'] ?? [];
        $isSuccessful = true;
        $lastFlag = '';

        foreach (is_array($globs) ? $globs : [] as $glob) {
            foreach (glob($glob) as $composerFolder) {
                $newFlag = sprintf('-d "%s"', dirname($composerFolder), $options['flags']);

                if ($lastFlag) {
                    $options['flags'] = str_replace($lastFlag, $newFlag, $options['flags']);
                } else {
                    $options['flags'] .= ' ' . $newFlag;
                }

                $lastFlag = $newFlag;

                $this->setOptions(array_merge(
                    $options,
                    ['flags' => trim($options['flags'])],
                ));

                $isSuccessful = parent::execute() && $isSuccessful;
            }
        }

        return $isSuccessful;
    }

    /**
     * Returns an empty glob list.
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return [
            'globs' => [],
        ];
    }

    /**
     * Get a short Description of the Task with the used globs.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return sprintf(
            '[Composer] Sub-Install of %s',
            implode(',', $this->getOptions()['globs'] ?? []),
        );
    }

    /**
     * Get the Name/Code of the Task.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'composer/sub-install';
    }
}
