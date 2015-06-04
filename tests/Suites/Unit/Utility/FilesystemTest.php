<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as FilesystemApi;
use Etki\Testing\AllureFramework\Runner\Utility\UuidFactory;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests filesystem utility class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Utility
 * @author  Etki <etki@etki.name>
 */
class FilesystemTest extends AbstractClassAwareTest
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Filesystem API FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_API_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem';
    /**
     *  Symfony filesystem FQCN.
     *
     * @since 0.1.0
     */
    const SYMFONY_FILESYSTEM_CLASS = 'Symfony\Component\Filesystem\Filesystem';
    /**
     * UUID factory FQCN.
     *
     * @since 0.1.0
     */
    const UUID_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\UuidFactory';
    /**
     * Symfony's Filesystem I/O exception class.
     *
     * @since 0.1.0
     */
    const IO_EXCEPTION_CLASS
        = 'Symfony\Component\Filesystem\Exception\IOException';
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
     * Creates test instance.
     *
     * @param FilesystemApi     $filesystemApi     PHP filesystem API object.
     * @param SymfonyFilesystem $symfonyFilesystem Symfony filesystem instance.
     * @param UuidFactory       $uuidFactory       UUID provider.
     *
     * @return Filesystem
     * @since 0.1.0
     */
    protected function createTestInstance(
        FilesystemApi $filesystemApi = null,
        SymfonyFilesystem $symfonyFilesystem = null,
        UuidFactory $uuidFactory = null
    ) {
        $filesystemApi = $filesystemApi ?: $this->createFilesystemApiMock();
        if (!$symfonyFilesystem) {
            $symfonyFilesystem = $this->createSymfonyFilesystemMock();
        }
        if (!$uuidFactory) {
            $uuidFactory = $this
                ->getMockFactory(self::UUID_FACTORY_CLASS)
                ->getMock();
        }
        $instance = parent::createTestInstance(
            $filesystemApi,
            $symfonyFilesystem,
            $uuidFactory
        );
        return $instance;
    }

    /**
     * Creates Filesystem API mock.
     *
     * @return FilesystemApi|Mock
     * @since 0.1.0
     */
    private function createFilesystemApiMock()
    {
        $filesystemApi = $this
            ->getMockFactory(self::FILESYSTEM_API_CLASS)
            ->getMock();
        return $filesystemApi;
    }

    /**
     * Creates Symfony Filesystem component mock.
     *
     * @return SymfonyFilesystem|Mock
     * @since 0.1.0
     */
    private function createSymfonyFilesystemMock()
    {
        $symfonyFilesystem = $this
            ->getMockFactory(self::SYMFONY_FILESYSTEM_CLASS)
            ->getMock();
        return $symfonyFilesystem;
    }
    
    // data providers
    
    /**
     * Provides paths for normalization.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function pathNormalizationProvider()
    {
        return array(
            array(
                '../tests/./data/../Suites/Unit/',
                null,
                '../tests/Suites/Unit/',
            ),
            array('bin/../local/bin/allure', '/usr', '/usr/local/bin/allure',),
        );
    }

    // tests

    /**
     * Tests `normalizePath()` method.
     *
     * @param string $rawPath          Path to normalize.
     * @param string $currentDirectory Fake current working directory.
     * @param string $expectedResult   Expected result.
     *
     * @dataProvider pathNormalizationProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testPathNormalization(
        $rawPath,
        $currentDirectory,
        $expectedResult
    ) {
        $filesystem = $this->createTestInstance();
        $this->assertSame(
            $expectedResult,
            $filesystem->normalizePath($rawPath, $currentDirectory)
        );
    }

    /**
     * Tests temporary file creation.
     *
     * @return void
     * @since 0.1.0
     */
    public function testTemporaryFileCreation()
    {
        $filesystemApi = $this->createFilesystemApiMock();
        $filesystemApi
            ->expects($this->atLeastOnce())
            ->method('createTemporaryFile')
            ->willReturnCallback(
                function ($directory, $prefix) {
                    $path = $directory . DIRECTORY_SEPARATOR . uniqid($prefix);
                    return $path;
                }
            );
        $instance = $this->createTestInstance($filesystemApi);
        $instance->createTemporaryFile('test', 'test');
    }


    /**
     * Tests temporary directory creation.
     *
     * @return void
     * @since 0.1.0
     */
    public function testTemporaryDirectoryCreation()
    {
        $this->createTestInstance()->createTemporaryDirectory();
    }
    
    // @codingStandardsIgnoreStart
    
    /**
     * Verifies that filesystem behaves on temporary file creation failure just
     * as expected.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem\TemporaryNodeCreationException
     *
     * @return void
     * @since 0.1.0
     */
    public function testTemporaryFileCreationFailure()
    {
        // @codingStandardsIgnoreEnd
        $filesystemApi = $this->createFilesystemApiMock();
        $filesystemApi
            ->expects($this->any())
            ->method('createTemporaryFile')
            ->willReturn(false);
        $this->createTestInstance($filesystemApi)->createTemporaryFile();
    }

    // @codingStandardsIgnoreStart
    
    /**
     * Verifies that filesystem behaves on temporary file creation failure just
     * as expected.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem\TemporaryNodeCreationException
     *
     * @return void
     * @since 0.1.0
     */
    public function testTemporaryDirectoryCreationFailure()
    {
        // @codingStandardsIgnoreEnd
        $exceptionClass = self::IO_EXCEPTION_CLASS;
        $filesystem = $this->createSymfonyFilesystemMock();
        $filesystem
            ->expects($this->any())
            ->method('mkdir')
            ->willThrowException(new $exceptionClass(''));
        $this
            ->createTestInstance(null, $filesystem)
            ->createTemporaryDirectory();
    }
}
