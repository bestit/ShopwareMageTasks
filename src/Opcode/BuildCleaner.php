<?php

declare(strict_types=1);

namespace BestIt\Mage\Tasks\Opcode;

use Mage\Task\AbstractTask;
use function sprintf;

/**
 * Saves a clean script in the given document root to clean the opcode/apc-cache in the context of the webserver.
 *
 * @package BestIt\Mage\Tasks\Opcode
 */
class BuildCleaner extends AbstractTask
{
    /**
     * The default document root for the cleaner.
     *
     * @internal
     * @var string
     */
    public const DEFAULT_DOC_ROOT = '.';

    /**
     * Returns document where the cleaner is saved.
     *
     * @return string
     */
    private function getDocumentRoot(): string
    {
        return $this->options['doc_root'] ?? self::DEFAULT_DOC_ROOT;
    }

    /**
     * Get the Name/Code of the Task.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'opcode/build-cleaner';
    }

    /**
     * Get a short Description of the Task.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return sprintf(
            '[opcode] Creates the cleaner in %s',
            $this->getDocumentRoot(),
        );
    }

    /**
     * Saves a clean script in the given document root to clean the opcode/apc-cache in the context of the webserver.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $template = "<?php

declare(strict_types=1);

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
}

if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}";

        $process = $this->runtime->runCommand(sprintf(
            'echo "%s" > %s/apc_clear.php',
            $template,
            $this->getDocumentRoot(),
        ));

        return $process->isSuccessful();
    }
}
