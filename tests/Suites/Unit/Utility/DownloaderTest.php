<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Rhumsaa\Uuid\Uuid;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class tests downloader.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility
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
     * Guzzle client FQCN.
     *
     * @since 0.1.0
     */
    const GUZZLE_CLIENT_CLASS = 'Guzzle\Http\Client';
    /**
     * Guzzle request FQIN.
     *
     * @since 0.1.0
     */
    const GUZZLE_REQUEST_INTERFACE = 'Guzzle\Http\Message\RequestInterface';
    /**
     * Filesystem helper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_HELPER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

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
     * Creates test instance.
     *
     * @param string      $responseBody   Guzzle response body.
     * @param string|null $writtenContent This variable will be populated by
     *                                    content passed to be written to file.
     *
     * @return Downloader
     * @since 0,1.0
     */
    protected function createTestInstance($responseBody, &$writtenContent = null)
    {
        $guzzleMock = $this->createGuzzleMock($responseBody);
        $filesystemMock = $this->createFilesystemMock($writtenContent);
        return parent::createTestInstance($guzzleMock, $filesystemMock);
    }

    /**
     * Creates Guzzle client mock.
     *
     * @param string $responseBody Response body to return.
     *
     * @return Guzzle|Mock
     * @since 0.1.0
     */
    private function createGuzzleMock($responseBody)
    {
        $guzzleResponse = new Response(200, null, $responseBody);
        /** @type RequestInterface|Mock $guzzleRequestMock */
        $guzzleRequestMock = $this
            ->getMockFactory(self::GUZZLE_REQUEST_INTERFACE)
            ->getConstructorlessMock();
        $guzzleRequestMock
            ->expects($this->atLeastOnce())
            ->method('send')
            ->willReturn($guzzleResponse);
        /** @type Guzzle|Mock $guzzleMock */
        $guzzleMock = $this
            ->getMockFactory(self::GUZZLE_CLIENT_CLASS)
            ->getConstructorlessMock();
        $guzzleMock
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($guzzleRequestMock);
        return $guzzleMock;
    }

    /**
     * Creates filesystem mock.
     *
     * @param string|null $writtenContent Reference that will absorb content
     *                                    written to a file.
     *
     * @return Filesystem|Mock
     * @since 0.1.0
     */
    protected function createFilesystemMock(&$writtenContent = null)
    {
        /** @type Filesystem|Mock $filesystemMock */
        $filesystemMock = $this
            ->getMockFactory(self::FILESYSTEM_HELPER_CLASS)
            ->getConstructorlessMock();
        $filesystemMock
            ->expects($this->atLeastOnce())
            ->method('writeFile')
            ->willReturnCallback(
                function ($path, $content) use (&$writtenContent) {
                    $writtenContent = $content;
                    return $path; // fooling static analysis tools
                }
            );
        return $filesystemMock;
    }

    /**
     * Verifies that download uses other components as expected.
     *
     * @return void
     * @since 0.1.0
     */
    public function testDownload()
    {
        $responseBody = Uuid::uuid4()->__toString();
        $writtenContent = null;
        $instance = $this->createTestInstance($responseBody, $writtenContent);
        $instance->download('http://' . Uuid::uuid4(), Uuid::uuid4());
        $this->assertSame($responseBody, $writtenContent);
    }
}
