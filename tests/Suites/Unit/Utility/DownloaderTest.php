<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Codeception\TestCase\Test;
use UnitTester;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Rhumsaa\Uuid\Uuid;
use VirtualFileSystem\FileSystem as VFS;
use VirtualFileSystem\Structure\Node;

/**
 * This class tests downloader.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility
 * @author  Etki <etki@etki.name>
 */
class DownloaderTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Downloader';
    /**
     * Fake url to download file from.
     *
     * @since 0.1.0
     */
    const TEST_FILE_URL = 'http://test/test.txt';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    /**
     * VFS instance.
     *
     * @type VFS
     * @since 0.1.0
     */
    private $filesystem;

    /**
     * Creates test instance.
     *
     * @return Downloader
     * @since 0.1.0
     */
    private function createTestInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }

    // @codingStandardsIgnoreStart

    /**
     * Before-test hook, creates VFS.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return void
     * @since 0.1.0
     */
    protected function _before()
    {
        $this->filesystem = new VFS;
    }

    /**
     * After-test hook, destroys VFS.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return void
     * @since 0.1.0
     */
    protected function _after()
    {
        $root = $this->filesystem->root();
        // being double sure everything gets deleted
        /** @type Node $child */
        foreach ($root->children() as $child) {
            $root->remove($child->basename());
        }
        unset($this->filesystem);
    }
    
    // @codingStandardsIgnoreEnd

    // tests
    
    /**
     * Tests downloading.
     *
     * @return void
     * @since 0.1.0
     */
    public function testDownload()
    {
        $instance = $this->createTestInstance();
        $guzzleMock = new MockPlugin;
        $instance->addGuzzlePlugin($guzzleMock);
        
        $fakeResponse = new Response(200, null, Uuid::uuid4());
        $guzzleMock->addResponse($fakeResponse);
        
        $target = $this->filesystem->path('/' . Uuid::uuid4());
        
        $this->assertFalse(file_exists($target));
        $instance->download(self::TEST_FILE_URL, $target);
        $this->assertSame(
            $fakeResponse->getBody(true),
            file_get_contents($target)
        );
    }
}
