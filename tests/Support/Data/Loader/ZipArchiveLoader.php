<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader;

use Codeception\Configuration;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\ZipArchiveMetadata;
use RuntimeException;

/**
 * Loads zip archive data.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader
 * @author  Etki <etki@etki.name>
 */
class ZipArchiveLoader
{
    /**
     * Path to archives inside data directory.
     *
     * @since 0.1.0
     */
    const ARCHIVE_DIR = 'Samples/Allure/Package';
    /**
     * List of loaded packages.
     *
     * @type ZipArchiveMetadata[]
     * @since 0.1.0
     */
    private $archives = array();

    /**
     * Loads single archive data.
     *
     * @param string $name Archive name.
     *
     * @return ZipArchiveMetadata
     * @since 0.1.0
     */
    public function loadArchive($name)
    {
        if (isset($this->archives[$name])) {
            return $this->archives[$name];
        }
        $name = basename($name, '.zip') . '.zip';
        $dataDir = rtrim(Configuration::dataDir(), '\\/');
        $path = $dataDir . '/' . self::ARCHIVE_DIR . '/' . $name;
        $realPath = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $metadataPath = $realPath . '.metadata.json';
        $this->assertFileExists($realPath);
        $this->assertFileExists($metadataPath);
        $metadata = json_decode(file_get_contents($metadataPath), true);
        $instance = new ZipArchiveMetadata;
        $instance->setMetadata($metadata);
        $instance->setPath($realPath);
        return $this->archives[$name] = $instance;
    }

    /**
     * Ensures that file exists or throws exception.
     *
     * @param string $path Path to file.
     *
     * @throws RuntimeException Thrown in case file is missing.
     *
     * @return void
     * @since 0.1.0
     */
    private function assertFileExists($path)
    {
        if (!file_exists($path)) {
            $message = sprintf('File `%s` doesn\'t exist', $path);
            throw new RuntimeException($message);
        }
    }
}
