<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\ZipArchiveFactory;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * Another formal test.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility
 * @author  Etki <etki@etki.name>
 */
class ExtractorTest extends AbstractClassAwareTest
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Extractor';
    /**
     * Filesystem helper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_HELPER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Temporary filesystem nodes manager FQCN.
     *
     * @since 0.1.0
     */
    const TEMPORARY_NODES_MANAGER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager';
    /**
     * Archive factory class.
     *
     * @since 0.1.0
     */
    const ZIP_ARCHIVE_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\ZipArchiveFactory';
    /**
     * Archive class.
     *
     * @since 0.1.0
     */
    const ZIP_ARCHIVE_CLASS = 'ZipArchive';
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
     * Returns new extractor.
     *
     * @param Filesystem            $filesystem            Filesystem helper
     *                                                     instance.
     * @param TemporaryNodesManager $temporaryNodesManager Temporary filesystem
     *                                                     nodes manager.
     * @param ZipArchiveFactory     $zipArchiveFactory     Zip archive factory.
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
        if (!$filesystem) {
            $filesystem = $this
                ->getMockFactory(self::FILESYSTEM_HELPER_CLASS)
                ->getConstructorlessMock();
        }
        if (!$temporaryNodesManager) {
            $temporaryNodesManager = $this
                ->getMockFactory(self::TEMPORARY_NODES_MANAGER_CLASS)
                ->getConstructorlessMock();
        }
        if (!$zipArchiveFactory) {
            $zipArchiveMock = $this
                ->getMockFactory(self::ZIP_ARCHIVE_CLASS)
                ->getConstructorlessMock();
            $zipArchiveFactory = $this
                ->getMockFactory(self::ZIP_ARCHIVE_FACTORY_CLASS)
                ->getConstructorlessMock();
            $zipArchiveFactory
                ->expects($this->any())
                ->method('getZipArchive')
                ->willReturn($zipArchiveMock);
        }
        $instance = parent::createTestInstance(
            $filesystem,
            $temporaryNodesManager,
            $zipArchiveFactory
        );
        return $instance;
    }

    /**
     * Creates prepared extractor instance.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Extractor
     * @since 0.1.0
     */
    private function createPreparedTestInstance()
    {
        $zipArchiveMock = $this
            ->getMockFactory(self::ZIP_ARCHIVE_CLASS)
            ->getMock();
        $zipArchiveMock
            ->expects($this->atLeastOnce())
            ->method('extractTo');
        $zipArchiveFactoryMock = $this
            ->getMockFactory(self::ZIP_ARCHIVE_FACTORY_CLASS)
            ->getMock();
        $zipArchiveFactoryMock
            ->expects($this->once())
            ->method('getZipArchive')
            ->willReturn($zipArchiveMock);
        $temporaryNodesManagerMock = $this
            ->getMockFactory(self::TEMPORARY_NODES_MANAGER_CLASS)
            ->getConstructorlessMock();
        $temporaryNodesManagerMock
            ->expects($this->any())
            ->method('createTemporaryFile')
            ->willReturnCallback(
                function () {
                    return uniqid();
                }
            );
        $temporaryNodesManagerMock
            ->expects($this->any())
            ->method('createTemporaryDirectory')
            ->willReturnCallback(
                function () {
                    return uniqid();
                }
            );
        $instance = $this->createTestInstance(
            null,
            $temporaryNodesManagerMock,
            $zipArchiveFactoryMock
        );
        return $instance;
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
        $instance = $this->createPreparedTestInstance();
        $instance->extractFile(uniqid(), uniqid(), uniqid());
    }
}
