<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Utility;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem as FilesystemApi;
use Etki\Testing\AllureFramework\Runner\Utility\UuidFactory;
use Guzzle\Http\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use FunctionalTester;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Rhumsaa\Uuid\Uuid;
use VirtualFileSystem\FileSystem as VFS;

/**
 * This tests verifies that downloader works as expected.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Utility
 * @author  Etki <etki@etki.name>
 */
class DownloaderTest extends AbstractClassAwareTest
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Downloader';
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
     * @since 0.1.0
     */
    private $filesystem;

    // utility methods

    /**
     * Returns tested class.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }

    /**
     * Creates downloader instance.
     *
     * @param EventSubscriberInterface[] $plugins    List of Guzzle plugins.
     * @param Filesystem                 $filesystem Filesystem instance.
     *
     * @return Downloader
     * @since 0.1.0
     */
    protected function createTestInstance(
        array $plugins = array(),
        Filesystem $filesystem = null
    ) {
        $guzzle = new Client;
        foreach ($plugins as $plugin) {
            $guzzle->addSubscriber($plugin);
        }
        if (!$filesystem) {
            $filesystem = new Filesystem(
                new FilesystemApi,
                new SymfonyFilesystem,
                new UuidFactory
            );
        }
        return parent::createTestInstance($guzzle, $filesystem);
    }

    /**
     * Before-test hook, creates VFS.
     *
     * @return void
     * @since 0.1.0
     */
    protected function setUp()
    {
        parent::setUp();
        $this->filesystem = new VFS;
    }

    /**
     * After-test hook, destroys VFS.
     *
     * @return void
     * @since 0.1.0
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($this->filesystem);
    }

    // tests

    /**
     * Tests downloading.
     *
     * @return void
     * @since 0.1.0
     */
    public function testDownload()
    {
        $guzzleMock = new MockPlugin;
        $instance = $this->createTestInstance(array($guzzleMock,));

        $fakeResponse = new Response(200, null, Uuid::uuid4());
        $guzzleMock->addResponse($fakeResponse);

        $target = $this->filesystem->path('/' . Uuid::uuid4());

        $this->assertFalse(file_exists($target));
        $testUrl = 'http://' . Uuid::uuid4();
        $instance->download($testUrl, $target);
        $this->assertSame(
            $fakeResponse->getBody(true),
            file_get_contents($target)
        );
    }
}
