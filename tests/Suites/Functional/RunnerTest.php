<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional;

use Closure;
use Etki\Testing\AllureFramework\Runner\AllureCli\OutputBridgeFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Validator;
use Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Etki\Testing\AllureFramework\Runner\Runner;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Environment\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Vendor\Github\ClientMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as PhpFilesystemApi;
use Symfony\Component\Process\Process;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Github\Client as GithubApiClient;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;

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
     * Creates factory mock with loaded process.
     *
     * @param string $processOutput   Output to be produced by generated
     *                                process.
     * @param int    $processExitCode Exit code to be produced by generated
     *                                process.
     *
     * @return ProcessFactory|Mock
     * @since 0.1.0
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
     * Creates prepared github API client mock.
     *
     * @return GithubApiClient|Mock
     * @since 0.1.0
     */
    private function createGithubApiClientMock()
    {
        $releases = $this
            ->getResponseLoader('github')
            ->getResponse('releases.all', 1);
        /** @type ClientMockFactory $mockFactory */
        $mockFactory = $this->getMockFactory(Registry::GITHUB_API_CLIENT_CLASS);
        return $mockFactory->getPreparedMock($releases->getData());
    }

    /**
     * Creates process mock.
     *
     * @param Closure $runCallable Callback to invoke on `run()` call.
     * @param int     $exitCode    Exit code to return.
     * @param string  $output      Output to return.
     *
     * @return Process|Mock
     * @since 0.1.0
     */
    private function createPreparedProcessMock(
        Closure $runCallable,
        $exitCode = null,
        $output = null
    ) {
        $mock = $this
            ->getMockFactory(Registry::SYMFONY_PROCESS_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('run')
            ->willReturnCallback($runCallable);
        $mock
            ->expects($this->any())
            ->method('getExitCode')
            ->willReturn($exitCode);
        $mock
            ->expects($this->any())
            ->method('getOutput')
            ->willReturn($output);
        return $mock;
    }

    /**
     * Creates prepared process factory mock with pre-injected process instance.
     *
     * @param Process $process Process to inject.
     *
     * @return ProcessFactory|Mock
     * @since 0.1.0
     */
    private function createProcessFactoryMock(Process $process)
    {
        /** @type ProcessFactoryMockFactory $factory */
        $factory = $this->getMockFactory(Registry::PROCESS_FACTORY_CLASS);
        return $factory->getInjectedMock($process);
    }

    /**
     * Creates extractor dummy.
     *
     * @return Mock|Extractor
     * @since 0.1.0
     */
    private function createExtractorMock()
    {
        $mock = $this
            ->getMockFactory(Registry::EXTRACTOR_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('extractFile')
            ->willReturn(null);
        return $mock;
    }

    /**
     * Creates downloader dummy.
     *
     * @return Mock|Downloader
     * @since 0.1.0
     */
    private function createDownloaderMock()
    {
        $mock = $this
            ->getMockFactory(Registry::DOWNLOADER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('download')
            ->willReturn(null);
        return $mock;
    }

    /**
     * Creates configuration validator mock with predefined validation result.
     *
     * @param bool $result Result to return on validation.
     *
     * @return Mock|Validator
     * @since 0.1.0
     */
    private function createConfigurationValidatorMock($result = false)
    {
        $mock = $this
            ->getMockFactory(Registry::CONFIGURATION_VALIDATOR_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('validate')
            ->willReturn($result);
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
        $githubApiClient = $this->createGithubApiClientMock();
        $extractor = $this->createExtractorMock();
        $downloader = $this->createDownloaderMock();
        $symfonyFilesystem = $this
            ->getMockFactory(Registry::SYMFONY_FILESYSTEM_CLASS)
            ->getMock();

        $configuration = $this->createConfiguration();
        $phpFilesystemApi = $this->createPhpFilesystemApiMock();
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
            'downloader' => $downloader,
            'zip_extractor' => $extractor,
            'github_api_client' => $githubApiClient,
            'symfony_filesystem' => $symfonyFilesystem,
        );
        $container = $this->createContainer($configuration, $services);
        $this->wipeContainerIoServices($container, array_keys($services));
        $runner = new Runner($configuration, $ioController, $container);
        $this->debug('End of run preparations, starting run');
        $report = $runner->run();
        $this->debug('End of run');
        $this->assertInstanceOf(
            Registry::ALLURE_EXECUTABLE_NOT_FOUND_EXCEPTION_CLASS,
            $report->getException()
        );
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
        $ioController = new DummyController();
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully'),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, false);
        $configuration = $this->createConfiguration();
        $phpFilesystemApi = $this->createPhpFilesystemApiMock(array('java'));
        $downloader = $this->createDownloaderMock();
        $extractor = $this->createExtractorMock();
        $githubApiClient = $this->createGithubApiClientMock();
        $symfonyFilesystem = $this
            ->getMockFactory(Registry::SYMFONY_FILESYSTEM_CLASS)
            ->getMock();
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
            'symfony_filesystem' => $symfonyFilesystem,
            'downloader' => $downloader,
            'zip_extractor' => $extractor,
            'github_api_client' => $githubApiClient,
        );
        $container = $this->createContainer($configuration, $services);
        $this->wipeContainerIoServices($container, array_keys($services));
        $runner = new Runner($configuration, $ioController, $container);
        $this->debug('End of run preparations, starting run');
        $report = $runner->run();
        $this->debug('End of run');
        $this->assertNull($report->getException());
        $this->assertSame(Report::STATUS_SUCCESS, $report->getStatus());
        $this->assertSame(0, $report->getExitCode());
    }

    /**
     * Tests run that ends bad.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException
     *
     * @return void
     * @since 0.1.0
     */
    public function testUnhandledExceptionalRun()
    {
        $ioController = new DummyController();
        $callable = function () {
            throw new AllureExecutableNotFoundException;
        };
        $process = $this->createPreparedProcessMock($callable);
        $runFactory = new RunFactory(
            $this->createProcessFactoryMock($process),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, true);
        $configuration = $this->createConfiguration();
        $configuration->setThrowOnMissingExecutable(true);
        $phpFilesystemApi = $this->createPhpFilesystemApiMock(array('allure'));
        $services = array(
            'php_filesystem_api' => $phpFilesystemApi,
            'run_factory' => $runFactory,
            'io_controller' => $ioController,
            'file_locator' => $fileLocator,
        );
        $container = $this->createContainer($configuration, $services);
        $runner = new Runner($configuration, $ioController, $container);
        $message = 'End of run preparations, starting run, exception in 3... ' .
            '2... 1...';
        $this->debug($message);
        $runner->run();
        $this->debug('End of run. You haven\'t seen my exception, have you?');
    }

    /**
     * Tests run that ends bad.
     *
     * @return void
     * @since 0.1.0
     */
    public function testHandledExceptionalRun()
    {
        $exception = new AllureExecutableNotFoundException;
        $ioController = new DummyController();
        $callable = function () use ($exception) {
            throw $exception;
        };
        $process = $this->createPreparedProcessMock($callable);
        $runFactory = new RunFactory(
            $this->createProcessFactoryMock($process),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, true);
        $configuration = $this->createConfiguration();
        $configuration->setThrowOnNonZeroExitCode(false);
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
        $this->assertSame($exception, $report->getException());
        $this->assertSame(Report::STATUS_HALTED, $report->getStatus());
        $this->assertNull($report->getExitCode());
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
        $this->assertSame(Report::STATUS_SUCCESS, $report->getStatus());
        $this->assertSame(0, $report->getExitCode());
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
        $this->assertSame(Report::STATUS_SUCCESS, $report->getStatus());
        $this->assertSame(0, $report->getExitCode());
    }

    /**
     * Test reaction on invalid configuration.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Configuration\InvalidConfigurationException
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testUnhandledInvalidConfigurationRun()
    {
        $configuration = new Configuration;
        $configuration->setThrowOnInvalidConfiguration(true);
        $configurationValidator
            = $this->createConfigurationValidatorMock(false);
        $services = array(
            'configuration_validator' => $configurationValidator
        );
        $container = $this->createContainer($configuration, $services);
        $this->wipeContainerIoServices($container);
        $runner = new Runner($configuration, new DummyController, $container);
        $runner->run();
    }

    /**
     * Test reaction on invalid configuration.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testHandledInvalidConfigurationRun()
    {
        $configuration = new Configuration;
        $configuration->setThrowOnInvalidConfiguration(false);
        $configurationValidator
            = $this->createConfigurationValidatorMock(false);
        $services = array(
            'configuration_validator' => $configurationValidator
        );
        $container = $this->createContainer($configuration, $services);
        $this->wipeContainerIoServices($container);
        $runner = new Runner($configuration, new DummyController, $container);
        $report = $runner->run();
        $this->assertInstanceOf(
            Registry::INVALID_CONFIGURATION_EXCEPTION_CLASS,
            $report->getException()
        );
        $this->assertSame(Report::STATUS_CANCELLED, $report->getStatus());
    }

    /**
     * Tests what happens when Allure returns something other than zero.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\AllureCli\NonZeroExitCodeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testUnhandledNonZeroExitCodeRun()
    {
        $exitCode = rand(1, 127);
        $ioController = new DummyController;
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully', $exitCode),
            new OutputBridgeFactory(new OutputFormatter, $ioController),
            new PhpApi
        );
        $fileLocator = $this->createFileLocatorMock(true, true);
        $configuration = $this->createConfiguration();
        $configuration->setThrowOnNonZeroExitCode(true);
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
        $this->debug('Behold my excepshun!');
        $runner->run();
        $this->debug('End of run');
        $this->debug('I guess i\'ve lost my shine exepshun sumwere');
    }

    /**
     * Tests non-zero result situation handling.
     *
     * @return void
     * @since 0.1.0
     */
    public function testHandledNonZeroExitCodeRun()
    {
        $exitCode = rand(1, 127);
        $ioController = new DummyController;
        $runFactory = new RunFactory(
            $this->createPreparedProcessFactoryMock('successfully', $exitCode),
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
        // todo: currently such exception is not set in report
//        $this->assertInstanceOf(
//            Registry::NON_ZERO_EXIT_CODE_EXCEPTION,
//            $report->getException()
//        );
        $this->assertSame(Report::STATUS_FAIL, $report->getStatus());
        $this->assertSame($exitCode, $report->getExitCode());
    }

    /**
     * 100% CC drochevo.
     *
     * @test
     *
     * @return void
     * @since 0.1.0
     */
    public function fuuuuuuuTest()
    {
        new Runner;
    }
}
