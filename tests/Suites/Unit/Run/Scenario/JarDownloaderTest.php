<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarDownloader;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
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
class JarDownloaderTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JarDownloader';
    /**
     * FQCN for downloader utility.
     *
     * @since 0.1.0
     */
    const DOWNLOADER_FQCN
        = 'Etki\Testing\AllureFramework\Runner\Utility\Downloader';
    /**
     * FQCN for extractor utility.
     *
     * @since 0.1.0
     */
    const EXTRACTOR_FQCN
        = 'Etki\Testing\AllureFramework\Runner\Utility\Extractor';
    /**
     * FQCN for temporary filesystem nodes manager.
     *
     * @since 0.1.0
     */
    const TEMPORARY_NODES_MANAGER_FQCN
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    // utility methods
    
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
     * Returns IOControllerInterface mock.
     *
     * @return IOControllerInterface|Mock
     * @since 0.1.0
     */
    private function getIoControllerMock()
    {
        $mock = $this
            ->getMockFactory(self::IO_CONTROLLER_INTERFACE)
            ->getDummyMock();
        return $mock;
    }

    /**
     * Returns downloader utility mock.
     *
     * @return Downloader|Mock
     * @since 0.1.0
     */
    private function getDownloaderMock()
    {
        $mock = $this
            ->getMockFactory(self::DOWNLOADER_FQCN)
            ->getConstructorlessMock();
        return $mock;
    }

    /**
     * Returns extractor utility mock.
     *
     * @return Extractor|Mock
     * @since 0.1.0
     */
    private function getExtractorMock()
    {
        $mock = $this
            ->getMockFactory(self::EXTRACTOR_FQCN)
            ->getConstructorlessMock();
        return $mock;
    }

    /**
     * Returns Temporary nodes manager mock.
     *
     * @return TemporaryNodesManager|Mock
     * @since 0.1.0
     */
    private function getTemporaryNodesManagerMock()
    {
        $mock = $this
            ->getMockFactory(self::TEMPORARY_NODES_MANAGER_FQCN)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('createTemporaryFile')
            ->willReturnCallback(
                function () {
                    return uniqid();
                }
            );
        $mock
            ->expects($this->any())
            ->method('createTemporaryDirectory')
            ->willReturnCallback(
                function () {
                    return uniqid();
                }
            );
        return $mock;
    }

    /**
     * Creates jar downloader test instance.
     *
     * @param Downloader            $downloader            Underlying
     *                                                     downloader.
     * @param Extractor             $extractor             Zip file extractor.
     * @param TemporaryNodesManager $temporaryNodesManager Temporary nodes
     *                                                     manager.
     * @param IOControllerInterface $ioController          I/O controller
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return JarDownloader
     * @since 0.1.0
     */
    protected function createTestInstance(
        Downloader $downloader = null,
        Extractor $extractor = null,
        TemporaryNodesManager $temporaryNodesManager = null,
        IOControllerInterface $ioController = null
    ) {
        $downloader = $downloader ?: $this->getDownloaderMock();
        $extractor = $extractor ?: $this->getExtractorMock();
        if (!$temporaryNodesManager) {
            $temporaryNodesManager = $this->getTemporaryNodesManagerMock();
        }
        $ioController = $ioController ?: $this->getIoControllerMock();
        $instance = parent::createTestInstance(
            $downloader,
            $extractor,
            $temporaryNodesManager,
            $ioController
        );
        return $instance;
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
        $archivePath = null;
        $usedArchive = null;
        $downloader = $this->getDownloaderMock();
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
        $instance = $this->createTestInstance($downloader, $extractor);
        $instance->downloadJar('http://' . uniqid());
        $this->assertNotNull($archivePath);
        $this->assertSame($archivePath, $usedArchive);
    }
}
