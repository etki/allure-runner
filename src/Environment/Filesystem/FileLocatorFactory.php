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
     * Generated file locator.
     *
     * @type FileLocatorInterface
     * @since 0.1.0
     */
    private $fileLocator;
    
    /**
     * Initializer.
     *
     * @param Runtime        $runtime        Current environment runtime.
     * @param ProcessFactory $processFactory Process factory for file locator
     *                                       creation.
     *
     * @codeCoverageIgnore
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
        if (!isset($this->fileLocator)) {
            switch ($this->runtime->getOsFamily()) {
                case Runtime::FAMILY_MAC:
                    $locator = new MacOsFileLocator($this->processFactory);
                    break;
                case Runtime::FAMILY_WINDOWS:
                    $locator = new WindowsFileLocator($this->processFactory);
                    break;
                // unix by default -_-
                default:
                    $locator = new UnixFileLocator($this->processFactory);
                    break;
            }
            $this->fileLocator = $locator;
        }
        return $this->fileLocator;
    }
}
