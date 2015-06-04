<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\Filesystem;

/**
 * Simple cleaner service that performs clean-up at the end of the run.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility\Filesystem
 * @author  Etki <etki@etki.name>
 */
class Cleaner
{
    /**
     * Initializer.
     *
     * @param TemporaryNodesManager $temporaryNodesManager
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(TemporaryNodesManager $temporaryNodesManager)
    {
        $this->temporaryNodesManager = $temporaryNodesManager;
    }

    /**
     * Cleans everything up.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function cleanUp()
    {
        $this->temporaryNodesManager->removeTemporaryNodes();
    }
}
