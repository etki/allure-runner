<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

/**
 * Returns different project paths.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class PathResolver
{
    /**
     * Path to configuration directory.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_DIRECTORY = 'resources/configuration';
    
    /**
     * Project root directory path.
     *
     * @type string
     * @since 0.1.0
     */
    private $projectRoot;

    /**
     * Constructs new instance.
     *
     * @param string $projectRoot Project root.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct($projectRoot)
    {
        $this->projectRoot = rtrim($projectRoot, '\\/');
    }

    /**
     * Retrieves configuration directory.
     *
     * @return string
     * @since 0.1.0
     */
    public function getConfigurationDirectory()
    {
        $path = $this->projectRoot . DIRECTORY_SEPARATOR .
            self::CONFIGURATION_DIRECTORY;
        return $this->normalizePathSeparators($path);
    }

    /**
     * Returns path to configuration file with specified name.
     *
     * @param string $name Name of the file.
     *
     * @return string
     * @since 0.1.0
     */
    public function getConfigurationFile($name)
    {
        return $this->getConfigurationDirectory() . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * Normalizes path separators in provided path.
     *
     * @param string $path Path to normalize.
     *
     * @todo move to Utility/Filesystem?
     *
     * @return string
     * @since 0.1.0
     */
    protected function normalizePathSeparators($path)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return $path;
        }
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
