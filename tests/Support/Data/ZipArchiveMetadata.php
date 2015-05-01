<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data;

use BadMethodCallException;

/**
 * Single zip archive metadata.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data
 * @author  Etki <etki@etki.name>
 */
class ZipArchiveMetadata
{
    /**
     * Path to archive.
     *
     * @type string
     * @since 0.1.0
     */
    private $path;

    /**
     * List of md5 checksums for archive contents.
     *
     * @type array
     * @since 0.1.0
     */
    private $metadata = array();

    /**
     * Returns path.
     *
     * @return string
     * @since
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets path.
     *
     * @param string $path Path.
     *
     * @return $this Current instance.
     * @since
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Returns metadata.
     *
     * @return array
     * @since
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Sets metadata.
     *
     * @param array $metadata Metadata.
     *
     * @return $this Current instance.
     * @since
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Returns md5 hash for specified file.
     *
     * @param string $path File path inside archive.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMd5($path)
    {
        if (!isset($this->metadata[$path])) {
            $message = sprintf('No metadata for path `%s`', $path);
            throw new BadMethodCallException($message);
        }
        return $this->metadata[$path]['md5'];
    }
}
