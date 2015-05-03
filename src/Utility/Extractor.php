<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use ZipArchive;

/**
 * Extracts ZIP archives.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class Extractor
{
    /**
     * Creates and destroys temporary files/directories. Wroom wroom hetzneraka.
     *
     * @type TemporaryNodesManager
     * @since 0.1.0
     */
    private $temporaryNodesManager;
    /**
     * Filesystem helper instance.
     *
     * @type Filesystem
     * @since 0.1.0
     */
    private $filesystem;

    /**
     * Initializer.
     *
     * @param Filesystem            $filesystem            Filesystem helper.
     * @param TemporaryNodesManager $temporaryNodesManager Temporary file /
     *                                                     directory manager.
     *
     * @codeCoverageIgnore
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Filesystem $filesystem,
        TemporaryNodesManager $temporaryNodesManager
    ) {
        $this->temporaryNodesManager = $temporaryNodesManager;
        $this->filesystem = $filesystem;
    }

    /**
     * Extracts single file from archive to specified target.
     *
     * @param string $source Source file
     * @param string $file   File to extract.
     * @param string $target File to use as extract target.
     *
     * @return void
     * @since 0.1.0
     */
    public function extractFile($source, $file, $target)
    {
        $archive = new ZipArchive;
        $temporaryDirectory
            = $this->temporaryNodesManager->createTemporaryDirectory();
        $archive->open($source);
        $archive->extractTo($temporaryDirectory, $file);
        $filePath = $temporaryDirectory . DIRECTORY_SEPARATOR . $file;
        $this->filesystem->move($filePath, $target);
    }
}
