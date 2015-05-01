<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ZipArchiveLoader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\ZipArchiveMetadata;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use VirtualFileSystem\FileSystem as VFS;
use Codeception\Util\Fixtures;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests extractor class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility
 * @author  Etki <etki@etki.name>
 */
class ExtractorTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Extractor';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    /**
     * Loads archive data.
     *
     * @param string $name Archive name.
     *
     * @return ZipArchiveMetadata
     * @since 0.1.0
     */
    private function loadArchive($name)
    {
        /** @type ZipArchiveLoader $archiveLoader */
        $archiveLoader = Fixtures::get('service.testing.archive_loader');
        return $archiveLoader->loadArchive($name);
    }

    /**
     * Returns new extractor.
     *
     * @return Extractor
     * @since 0.1.0
     */
    private function createTestInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }

    // tests

    /**
     * Tests single file extraction.
     *
     * @return void
     * @since 0.1.0
     */
    public function testSingleFileExtraction()
    {
        $instance = $this->createTestInstance();
        $vfs = new VFS;
        $archive = 'allure-cli';
        $target = $vfs->path('allure-cli.jar');
        $metadata = $this->loadArchive($archive);
        $this->assertFalse(file_exists($target));
        $instance->extractFile(
            $metadata->getPath(),
            'lib/allure-cli.jar',
            $target
        );
        $this->assertTrue(file_exists($target));
        $this->assertSame(
            $metadata->getMd5('lib/allure-cli.jar'),
            md5(file_get_contents($target))
        );
    }
}
