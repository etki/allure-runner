<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Scenario;

use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarDownloader;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\IOControllerMockFactory;
use VirtualFileSystem\FileSystem as Vfs;
use Codeception\TestCase\Test;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class tests jar downloader.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarDownloaderTest extends Test
{
    /**
     * FQCN for downloader utility.
     *
     * @since 0.1.0
     */
    const DOWNLOADER_FQCN
        = '\Etki\Testing\AllureFramework\Runner\Utility\Downloader';
    /**
     * FQCN for extractor utility.
     *
     * @since 0.1.0
     */
    const EXTRACTOR_FQCN
        = '\Etki\Testing\AllureFramework\Runner\Utility\Extractor';
    /**
     * FQCN for PHP API wrapper.
     *
     * @since 0.1.0
     */
    const PHP_API_FQCN
        = '\Etki\Testing\AllureFramework\Runner\Utility\PhpApi';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    /**
     * Returns IOControllerInterface mock.
     *
     * @return IOControllerInterface|Mock
     * @since 0.1.0
     */
    private function getIoControllerMock()
    {
        $factory = new IOControllerMockFactory;
        return $factory->getMock($this);
    }

    /**
     * Returns downloader utility mock.
     *
     * @return Downloader|Mock
     * @since 0.1.0
     */
    private function getDownloaderMock()
    {
        return $this->getMock(self::DOWNLOADER_FQCN, array('download',));
    }

    /**
     * Returns extractor utility mock.
     *
     * @return Extractor|Mock
     * @since 0.1.0
     */
    private function getExtractorMock()
    {
        return $this->getMock(self::EXTRACTOR_FQCN, array('extractFile',));
    }

    /**
     * Returns PHP API mock.
     *
     * @return PhpApi|Mock
     * @since 0.1.0
     */
    private function getPhpApiMock()
    {
        $mock = $this->getMock(
            self::PHP_API_FQCN,
            array('getSystemTemporaryFilesDirectory',)
        );
        $vfs = new Vfs;
        $mock
            ->expects($this->any())
            ->method('getSystemTemporaryFilesDirectory')
            ->willReturnCallback(
                function () use ($vfs) {
                    return $vfs->path('/tmp');
                }
            );
        return $mock;
    }

    // tests

    /**
     * This is most ridiculous test in project, but i understood it only halfway
     * through. Basically it just erm tests that the right methods are called
     * and downloaded zip file is used on the next step.
     *
     * @return void
     * @since 0.1.0
     */
    public function testJarFileDownload()
    {
        $phpApi = $this->getPhpApiMock();
        $downloader = $this->getDownloaderMock();
        $archivePath = null;
        $downloader
            ->expects($this->atLeastOnce())
            ->method('download')
            ->willReturnCallback(
                function ($source, $target) use (&$archivePath) {
                    $archivePath = $target;
                    return $source; // static analyzers, you are fooled nao
                }
            );
        $extractor = $this->getExtractorMock();
        $usedArchive = null;
        $extractor
            ->expects($this->atLeastOnce())
            ->method('extractFile')
            ->willReturnCallback(
                function ($archive, $file, $target) use (&$usedArchive) {
                    $usedArchive = $archive;
                    return $file ? $target : $file; // another static analysis
                                                    // foolishment
                }
            );
        $ioController = $this->getIoControllerMock();
        $instance = new JarDownloader(
            $downloader,
            $extractor,
            $ioController,
            $phpApi
        );
        $instance->downloadJar('test');
        $this->assertNotNull($archivePath);
        $this->assertSame($archivePath, $usedArchive);
    }
}
