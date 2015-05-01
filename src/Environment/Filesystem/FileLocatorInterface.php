<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

/**
 * Defines interface of file locator which helps finding files.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
interface FileLocatorInterface
{
    /**
     * Locates executable file.
     *
     * @param string $name Name of the executable, e.g. allure.
     *
     * @return string|null Path to file.
     * @since 0.1.0
     */
    public function locateExecutable($name);

    /**
     * Locates file by name.
     *
     * @param string $name Name of the file.
     *
     * @return string Path to file.
     * @since 0.1.0
     */
    public function locateFile($name);
}
