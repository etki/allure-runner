<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;

/**
 * Shared functionality for all file locators.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractFileLocator implements FileLocatorInterface
{
    /**
     * Process factory instance.
     *
     * @type ProcessFactory
     * @since 0.1.0
     */
    private $processFactory;

    /**
     * Initializer.
     *
     * @param ProcessFactory $processFactory Process factory.
     *
     * @since 0.1.0
     */
    public function __construct(ProcessFactory $processFactory)
    {
        $this->processFactory = $processFactory;
    }

    /**
     * Retrieves process factory.
     *
     * @codeCoverageIgnore
     *
     * @return ProcessFactory
     * @since 0.1.0
     */
    protected function getProcessFactory()
    {
        return $this->processFactory;
    }
}
