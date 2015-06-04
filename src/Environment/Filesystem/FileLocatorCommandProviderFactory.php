<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider\MacOsCommandTemplateProvider;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider\UnixCommandTemplateProvider;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider\WindowsCommandTemplateProvider;
use Etki\Testing\AllureFramework\Runner\Environment\Runtime;
use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;

/**
 * Creates command template providers.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class FileLocatorCommandProviderFactory
{
    /**
     * Runtime object instance.
     *
     * @type Runtime
     * @since 0.1.0
     */
    private $runtime;

    /**
     * Initializer.
     *
     * @param Runtime $runtime Runtime object instance.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(Runtime $runtime)
    {
        $this->runtime = $runtime;
    }

    /**
     * Returns file location command template provider for current OS.
     *
     * @return FileLocationCommandProviderInterface
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplatesProvider()
    {
        switch ($this->runtime->getOsFamily()) {
            case Runtime::FAMILY_LINUX:
                // falling forward
            case Runtime::FAMILY_UNIX:
                return new UnixCommandTemplateProvider;
            case Runtime::FAMILY_MAC:
                return new MacOsCommandTemplateProvider;
            case Runtime::FAMILY_WINDOWS:
                return new WindowsCommandTemplateProvider;
            default:
                $message
                    = 'Couldn\'t find command template provider for current OS';
                throw new RuntimeException($message);
        }
    }

    /**
     * Returns executable location command template provider for current OS.
     *
     * @return ExecutableLocationCommandProviderInterface
     * @since 0.1.0
     */
    public function getExecutableLocationCommandTemplatesProvider()
    {
        switch ($this->runtime->getOsFamily()) {
            case Runtime::FAMILY_LINUX:
                // falling forward
            case Runtime::FAMILY_UNIX:
                return new UnixCommandTemplateProvider;
            case Runtime::FAMILY_MAC:
                return new MacOsCommandTemplateProvider;
            case Runtime::FAMILY_WINDOWS:
                return new WindowsCommandTemplateProvider;
            default:
                $message
                    = 'Couldn\'t find command template provider for current OS';
                throw new RuntimeException($message);
        }
    }
}
