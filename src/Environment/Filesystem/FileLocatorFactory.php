<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Environment\Runtime;

/**
 * Factory for generating file locator.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class FileLocatorFactory
{
    /**
     * Process factory for file locator creation.
     *
     * @type ProcessFactory
     * @since 0.1.0
     */
    private $processFactory;
    /**
     * Environment runtime instance.
     *
     * @type Runtime
     * @since 0.1.0
     */
    private $runtime;
    
    /**
     * Initializer.
     *
     * @param Runtime        $runtime        Current environment runtime.
     * @param ProcessFactory $processFactory Process factory for file locator
     *                                       creation.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Runtime $runtime,
        ProcessFactory $processFactory
    ) {
        $this->runtime = $runtime;
        $this->processFactory = $processFactory;
    }
    
    /**
     * Returns file locator implementation for current operating system.
     *
     * @codeCoverageIgnore
     *
     * @return FileLocatorInterface
     * @since 0.1.0
     */
    public function getFileLocator()
    {
        switch ($this->runtime->getOsFamily()) {
            case Runtime::FAMILY_MAC:
                return new MacOsFileLocator($this->processFactory);
            case Runtime::FAMILY_WINDOWS:
                return new WindowsFileLocator($this->processFactory);
            default:
                return new UnixFileLocator($this->processFactory);
        }
    }
}
