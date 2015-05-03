<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\PhpApi;

/**
 * Filesystem PHP API wrapper.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility\PhpApi
 * @author  Etki <etki@etki.name>
 */
class Filesystem
{
    /**
     * Tells if target at `$path` is an executable file.
     *
     * @param string $path Path to file.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     * @since 0.1.0
     */
    public function isExecutable($path)
    {
        return is_executable($path);
    }

    /**
     * Generates temporary file.
     *
     * @param string $directory Directory to place temporary file in.
     * @param string $prefix    Filename prefix.
     *
     * @codeCoverageIgnore
     *
     * @return string Path to file.
     * @since 0.1.0
     */
    public function generateTemporaryFile($directory, $prefix)
    {
        return tempnam($directory, $prefix);
    }

    /**
     * Returns location of temporary directory.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTemporaryDirectory()
    {
        return sys_get_temp_dir();
    }
}
