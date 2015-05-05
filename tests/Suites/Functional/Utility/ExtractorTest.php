<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Utility;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ZipArchiveLoader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\ZipArchiveFactory;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as FilesystemApi;
use Etki\Testing\AllureFramework\Runner\Utility\UuidFactory;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;
use VirtualFileSystem\FileSystem as VFS;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Another formal tests that just verifies that extractor really uses archive
 * API. Real testing comes with functional level.
 *
 * @version 0.1.0
 * @since   0.1,0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Utility
 * @author  Etki <etki@etki.name>
 */
class ExtractorTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Extractor';
    /**
     * Zip archive factory FQCN.
     *
     * @since 0.1.0
     */
    const ZIP_ARCHIVE_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\ZipArchiveFactory';
    /**
     * Filesystem helper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Filesystem PHP API wrapper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_API_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem';
    /**
     * Virtual filesystem temporary directory location.
     *
     * @since 0.1.0
     */
    const VFS_TEMPORARY_DIRECTORY = '/tmp';
    /**
     * Tester instance.
     *
     * @type FunctionalTester
     * @since 0.1.0
     */
    protected $tester;
    /**
     * VFS instance.
     *
     * @type VFS
     * @since
     */
    private $vfs;

    // utility methods

    /**
     * Before-test hook.
     *
     * @return void
     * @since 0.1.0
     */
    public function setUp()
    {
        parent::setUp();
        $this->vfs = new VFS;
        $this->vfs->createDirectory(self::VFS_TEMPORARY_DIRECTORY);
    }

    /**
     * After-test hook.
     *
     * @return void
     * @since 0.1.0
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->vfs);
    }

    /**
     * Returns test subject FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }

    /**
     * Creates instance for testing.
     *
     * @param Filesystem            $filesystem            Filesystem helper.
     * @param TemporaryNodesManager $temporaryNodesManager Temporary filesystem
     *                                                     nodes manager.
     * @param ZipArchiveFactory     $zipArchiveFactory     Factory that produces
     *                                                     zip archive objects.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Extractor
     * @since 0.1.0
     */
    protected function createTestInstance(
        Filesystem $filesystem = null,
        TemporaryNodesManager $temporaryNodesManager = null,
        ZipArchiveFactory $zipArchiveFactory = null
    ) {
        $filesystem = $filesystem ?: $this->createFilesystemMock();
        if (!$temporaryNodesManager) {
            $temporaryNodesManager = new TemporaryNodesManager($filesystem);
        }
        if (!$zipArchiveFactory) {
            $zipArchiveFactory = new ZipArchiveFactory;
        }
        $instance = parent::createTestInstance(
            $filesystem,
            $temporaryNodesManager,
            $zipArchiveFactory
        );
        return $instance;
    }

    /**
     * Creates filesystem helper mock.
     *
     * @return Filesystem
     * @since 0.1.0
     */
    private function createFilesystemMock()
    {
        /** @type FilesystemApi|Mock $filesystemApiMock */
        $filesystemApiMock = $this
            ->getMockFactory(self::FILESYSTEM_API_CLASS)
            ->getMock();
        $filesystemApiMock
            ->expects($this->any())
            ->method('getTemporaryDirectory')
            ->willReturn($this->getVfsTemporaryDirectory());
        $filesystem = new Filesystem(
            $filesystemApiMock,
            new SymfonyFilesystem,
            new UuidFactory
        );
        return $filesystem;
    }

    /**
     * Returns virtual temporary directory URL.
     *
     * @return string
     * @since 0.1.0
     */
    private function getVfsTemporaryDirectory()
    {
        return $this->vfs->path(self::VFS_TEMPORARY_DIRECTORY);
    }

    /**
     * Retrieves path of temporary file in VFS.
     *
     * @param string $relativePath Temporary file path relative to temporary
     *                             dir.
     *
     * @return string
     * @since 0.1.0
     */
    private function getVfsTemporaryFilePath($relativePath)
    {
        $path = $this->getVfsTemporaryDirectory() . DIRECTORY_SEPARATOR .
            $relativePath;
        return $path;
    }
    
    // data providers

    /**
     * Provides archive data.
     *
     * @return array
     * @since 0.1.0
     */
    public function archiveDataProvider()
    {
        $loader = new ZipArchiveLoader;
        $archive = $loader->loadArchive('allure-cli');
        $data = array();
        foreach ($archive->getFileList() as $relativeFilePath) {
            $data[] = array(
                $archive->getPath(),
                $relativeFilePath,
                $archive->getMd5($relativeFilePath)
            );
        }
        return $data;
    }
    
    // tests

    /**
     * Tests real extraction process.
     *
     * @param string $archive Archive location.
     * @param string $file    File to extract.
     * @param string $md5sum  File control sum.
     *
     * @dataProvider archiveDataProvider
     *
     * @since 0.1.0
     */
    public function testExtraction($archive, $file, $md5sum)
    {
        $extractor = $this->createTestInstance();
        $target = $this->getVfsTemporaryFilePath(basename($file));
        $extractor->extractFile($archive, $file, $target);
        
        $this->assertFileExists($target);
        $this->assertSame($md5sum, md5(file_get_contents($target)));
    }
}
