<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional;

use Etki\Testing\AllureFramework\Runner\AllureCli\OutputBridgeFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Etki\Testing\AllureFramework\Runner\Runner;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as PhpFilesystemApi;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;
use Symfony\Component\Process\Process;

/**
 * This class tests how run is going under different circumstances.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional
 * @author  Etki <etki@etki.name>
 */
class RunnerTest extends AbstractClassAwareTest
{
    /**
     * Tester instance.
     *
     * @type FunctionalTester
     * @since 0.1.0
     */
    protected $tester;

    /**
     * Returns tested class name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return Registry::RUNNER_CLASS;
    }

    // tests

    /**
     * Creates configuration instance.
     *
     * @param bool     $shouldDownloadMissingJar Whether to download missing jar
     *                                           or not.
     * @param string[] $sources                  Allure sources.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Configuration
     * @since 0.1.0
     */
    private function createConfiguration(
        $shouldDownloadMissingJar = true,
        array $sources = array('dummy',)
    ) {
        $configuration = new Configuration;
        $configuration->setDownloadMissingJar($shouldDownloadMissingJar);
        $configuration->addSources($sources);
        $configuration->setThrowOnMissingExecutable(false);
        $configuration->setThrowOnNonZeroExitCode(false);
        $configuration->setThrowOnInvalidConfiguration(false);
        return $configuration;
    }

    /**
     *
     *
     * @param     $processOutput
     * @param int $processExitCode
     *
     * @return ProcessFactory|Mock
     * @since
     */
    private function createPreparedProcessFactoryMock(
        $processOutput,
        $processExitCode = 0
    ) {
        $processMock = $this
            ->getMockFactory(Registry::SYMFONY_PROCESS_CLASS)
            ->getConstructorlessMock();
        $factoryMock = $this
            ->getMockFactory(Registry::PROCESS_FACTORY_CLASS)
            ->getMock();
        $processMock
            ->expects($this->any())
            ->method('run')
            ->willReturnCallback(
                function ($callback) use ($processOutput, $processExitCode) {
                    if ($callback) {
                        call_user_func($callback, Process::OUT, $processOutput);
                    }
                    return $processExitCode;
                }
            );
        $processMock
            ->expects($this->any())
            ->method('getExitCode')
            ->willReturn($processExitCode);
        $processMock
            ->expects($this->any())
            ->method('getOutput')
            ->willReturn($processOutput);
        $factoryMock
            ->expects($this->any())
            ->method('getProcess')
            ->willReturn($processMock);
        return $factoryMock;
    }


    /**
     * Creates file locator mock.
     *
     * @param bool $mockJavaSearch   Whether to mock java search.
     * @param bool $mockAllureSearch Whether to mock allure search.
     *
     * @return Mock
     * @since 0.1.0
     */
    private function createFileLocatorMock($mockJavaSearch, $mockAllureSearch)
    {
        $mock = $this
            ->getMockFactory(Registry::FILE_LOCATOR_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('locateExecutable')
            ->willReturnCallback(
                function ($executable) use ($mockJavaSearch, $mockAllureSearch) {
                    if ($mockJavaSearch && $executable === 'java') {
                        return array('java');
                    }
                    if ($mockAllureSearch && $executable === 'allure') {
                        return array('allure');
                    }
                    return null;
                }
            );
        if ($mockAllureSearch) {
            $mock
                ->expects($this->any())
                ->method('locateFile')
                ->willReturnCallback(
                    function ($file) {
                        $candidates = array('allure.jar', 'allure-cli.jar',);
                        if (in_array($file, $candidates, true)) {
                            return array($file,);
                        }
                        return null;
                    }
                );
        }
        return $mock;
    }

    /**
     * Creates PHP filesystem API mock.
     *
     * @param array $executables List of files that are considered to be
     *                           executable.
     *
     * @return PhpFilesystemApi|Mock
     * @since 0.1.0
     */
    private function createPhpFilesystemApiMock(array $executables = array())
    {
        $mock = $this
            ->getMockFactory(Registry::PHP_FILESYSTEM_API_CLASS)
            ->getMock();
        $mock
            ->expects($this->any())
            ->method('isExecutable')
            ->willReturnCallback(
                function ($path) use ($executables) {
                    return in_array($path, $executables, true);
                }
            );
        return $mock;
    }

    /**
     * Performs a run as if java hasn't been installed.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNoJavaRun()
    {
        $ioController = new DummyController;
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully'),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(false, false);
        $configuration = $this->createConfiguration();
        $phpFilesystemApi = $this->createPhpFilesystemApiMock();
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
        );
        $container = $this->createContainer($configuration, $services);
        $runner = new Runner($configuration, $ioController, $container);
        $this->debug('End of run preparations, starting run');
        $report = $runner->run();
        $this->debug('End of run');
        //$this->assertNull($report->getException());
        $this->assertSame($report->getStatus(), Report::STATUS_HALTED);
        $this->assertNull($report->getExitCode());
    }

    /**
     * Performs a run as if allure hasn't been installed.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNoAllureRun()
    {
        // todo
    }

    /**
     * Tests usual run with everything in place.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNormalRun()
    {
        $ioController = new DummyController;
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully'),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, true);
        $configuration = $this->createConfiguration();
        $phpFilesystemApi = $this->createPhpFilesystemApiMock(array('allure'));
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
        );
        $container = $this->createContainer($configuration, $services);
        $runner = new Runner($configuration, $ioController, $container);
        $this->debug('End of run preparations, starting run');
        $report = $runner->run();
        $this->debug('End of run');
        $this->assertNull($report->getException());
        $this->assertSame($report->getStatus(), Report::STATUS_SUCCESS);
        $this->assertSame($report->getExitCode(), 0);
    }

    /**
     * Tests run with allure specified in config.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNormalRunWithConfiguredAllure()
    {
        $ioController = new DummyController;
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully'),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, true);
        $configuration = $this->createConfiguration();
        $configuration->setExecutable('allure');
        $phpFilesystemApi = $this->createPhpFilesystemApiMock(array('allure'));
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
        );
        $container = $this->createContainer($configuration, $services);
        $runner = new Runner($configuration, $ioController, $container);
        $this->debug('End of run preparations, starting run');
        $report = $runner->run();
        $this->debug('End of run');
        $this->assertNull($report->getException());
        $this->assertSame($report->getStatus(), Report::STATUS_SUCCESS);
        $this->assertSame($report->getExitCode(), 0);
    }
}
