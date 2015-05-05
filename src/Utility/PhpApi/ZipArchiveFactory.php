<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\PhpApi;

use ZipArchive;

/**
 * This class produces ZIP archive class instances. Introduced only to remove
 * exclude new instance creation from code.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility\PhpApi
 * @author  Etki <etki@etki.name>
 */
class ZipArchiveFactory
{
    /**
     * Returns new zip archive.
     *
     * @codeCoverageIgnore
     *
     * @return ZipArchive
     * @since 0.1.0
     */
    public function getZipArchive()
    {
        return new ZipArchive;
    }
}
