<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Utility\Filesystem;

use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\Cleaner;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as FilesystemApi;
use FunctionalTester;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Etki\Testing\AllureFramework\Runner\Utility\UuidFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractTest;
use VirtualFileSystem\FileSystem as VFS;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This test verifies that no temporary files are left on filesystem after
 * cleaner cleanup.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Utility\Filesystem
 * @author  Etki <etki@etki.name>
 */
class CleanerTest extends AbstractTest
{
    /**
     * Filesystem API FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_API_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem';
    /**
     * Codeception tester instance.
     *
     * @type FunctionalTester
     * @since 0.1.0
     */
    protected $tester;
    /**
     * Virtual filesystem handle.
     *
     * @type VFS
     * @since 0.1.0
     */
    private $vfs;

    /**
     * Pre-test hook.
     *
     * @return void
     * @since 0.1.0
     */
    protected function setUp()
    {
        $this->vfs = new VFS;
    }

    /**
     * Post-test hook.
     *
     * @return void
     * @since 0.1.0
     */
    protected function tearDown()
    {
        unset($this->vfs);
    }

    /**
     * Returns VFS root directory.
     *
     * @return string
     * @since 0.1.0
     */
    private function getVfsRoot()
    {
        return $this->vfs->path('/');
    }

    /**
     * Creates filesystem API mock that is tied to virtual file system.
     *
     * @return FilesystemApi|Mock
     * @since 0.1.0
     */
    private function getPreparedFilesystemApiMock()
    {
        $mock = $this->getMockFactory(self::FILESYSTEM_API_CLASS)->getMock();
        $mock
            ->expects($this->any())
            ->method('getTemporaryDirectory')
            ->willReturn($this->getVfsRoot());
        // terribly wrong, but tempnam doesn't use file streams
        $mock
            ->expects($this->any())
            ->method('createTemporaryFile')
            ->willReturnCallback(
                function ($directory, $prefix) use ($mock) {
                    $uuidFactory = new UuidFactory;
                    $path = $directory . DIRECTORY_SEPARATOR . $prefix .
                        $uuidFactory->uuid4();
                    touch($path);
                    return $path;
                }
            );
        return $mock;
    }

    // tests

    /**
     * Tests cleaner service.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testCleaner()
    {
        $filesystemApi = $this->getPreparedFilesystemApiMock();
        $this->assertNotEmpty($filesystemApi->getTemporaryDirectory());
        $symfonyFilesystem = new SymfonyFilesystem;
        $uuidFactory = new UuidFactory;
        $filesystem = new Filesystem($filesystemApi, $symfonyFilesystem, $uuidFactory);
        $temporaryNodesManager = new TemporaryNodesManager($filesystem);
        $cleaner = new Cleaner($temporaryNodesManager);
        
        // $emptyDirContents = array('.', '..',);
        // php-vfs works a little but different than i would like to
        $emptyDirContents = array();
        sort($emptyDirContents);
        $contents = scandir($this->getVfsRoot());
        sort($contents);
        $this->assertEquals($emptyDirContents, $contents);
        $temporaryNodesManager->createTemporaryDirectory();
        $temporaryNodesManager->createTemporaryDirectory('dummy-dir-');
        $temporaryNodesManager->createTemporaryFile();
        $temporaryNodesManager->createTemporaryFile('dummy-file-');
        // $this->assertSame(6, count(scandir($this->getVfsRoot())));
        $this->assertSame(4, count(scandir($this->getVfsRoot())));
        $cleaner->cleanUp();
        $contents = scandir($this->getVfsRoot());
        sort($contents);
        $this->assertSame($emptyDirContents, $contents);
    }
}
