<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

/**
 * Utility filesystem class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class Filesystem
{
    /**
     * Normalizes path.
     *
     * @param string $path             Path to normalize.
     * @param string $currentDirectory Current working directory, if provided
     *                                 path is relative.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return string Normalized path.
     * @since 0.1.0
     */
    public function normalizePath($path, $currentDirectory = null)
    {
        $separator = DIRECTORY_SEPARATOR;
        if ($currentDirectory) {
            $path = $currentDirectory . $separator . $path;
        }
        $multipleSeparatorPattern = sprintf('~(?<!:)%s+~', $separator);
        $currentDirectoryPattern = sprintf('~%s.%s~', $separator, $separator);
        $parentDirectoryPattern = sprintf(
            '~%s[^%s]+%s..%s~',
            $separator,
            $separator,
            $separator,
            $separator
        );
        $normalizedPath = preg_replace(
            $multipleSeparatorPattern,
            $separator,
            $path
        );
        $normalizedPath = preg_replace(
            $currentDirectoryPattern,
            $separator,
            $normalizedPath
        );
        $normalizedPath = preg_replace(
            $parentDirectoryPattern,
            $separator,
            $normalizedPath
        );
        return $normalizedPath;
    }
}
