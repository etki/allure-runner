<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

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
     * Extracts single file from archive to specified target.
     *
     * @param string $source
     * @param string $file
     * @param string $target
     *
     * @return void
     * @since 0.1.0
     */
    public function extractFile($source, $file, $target)
    {
        $archive = new ZipArchive;
        $archive->open($source);
        $archive->extractTo($target, $file);
    }
}
