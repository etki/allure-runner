<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\AllureCli;

use Closure;
use Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilderFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser;
use Etki\Testing\AllureFramework\Runner\AllureCli\Run;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Exception;
use FunctionalTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Verifies that runner class pulls correct strings.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\AllureCli
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

    // utility methods
    
    /**
     * Returns test subject FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return Registry::ALLURE_CLI_RUNNER_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param RunFactory            $runFactory
     * @param CommandBuilderFactory $commandBuilderFactory
     * @param ResultOutputParser    $resultOutputParser
     * @param IOControllerInterface $ioController
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @return Runner
     * @since 0.1.0
     */
    protected function createTestInstance(
        RunFactory $runFactory = null,
        CommandBuilderFactory $commandBuilderFactory = null,
        ResultOutputParser $resultOutputParser = null,
        IOControllerInterface $ioController = null
    ) {
        $instance = parent::createTestInstance(
            $runFactory ?: $this->createRunFactoryMock(),
            $commandBuilderFactory ?: new CommandBuilderFactory,
            $resultOutputParser ?: $this->createResultOutputParserMock(),
            $ioController ?: new DummyController
        );
        return $instance;
    }
    
    // mockery

    /**
     * Creates run factory mock.
     *
     * @param Run $run Run to "create".
     *
     * @return Mock
     * @since 0.1.0
     */
    private function createRunFactoryMock(Run $run = null)
    {
        $run = $run ?: $this->createRunMock(0);
        $mock = $this
            ->getMockFactory(Registry::ALLURE_CLI_RUN_FACTORY_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getRun')
            ->willReturn($run);
        return $mock;
    }

    /**
     * Creates prepared factory mock.
     *
     * @param Closure $factory Real run factory.
     *
     * @return RunFactory|Mock
     * @since 0.1.0
     */
    private function createPreparedPreparedRunFactoryMock(Closure $factory)
    {
        $mock = $this
            ->getMockFactory(Registry::ALLURE_CLI_RUN_FACTORY_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getRun')
            ->willReturnCallback($factory);
        return $mock;
    }

    /**
     * Returns Allure CLI run mock
     *
     * @param int    $exitCode Exit code to return.
     * @param string $output   Run output.
     *
     * @return Run|Mock
     * @since 0.1.0
     */
    private function createRunMock($exitCode = 0, $output = '')
    {
        $mock = $this
            ->getMockFactory(Registry::ALLURE_CLI_RUN_CLASS)
            ->getConstructorlessMock();
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
     * Creates run mock that throws an exception during run.
     *
     * @param Exception $exception Exception instance.
     *
     * @return Run|Mock
     * @since 0.1.0
     */
    private function createExceptionalRunMock($exception)
    {
        $mock = $this
            ->getMockFactory(Registry::ALLURE_CLI_RUN_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('run')
            ->willThrowException($exception);
        return $mock;
    }

    /**
     * Creates result output parser mock.
     *
     * @param bool|null $verdict Verdict to return on output analysis.
     *
     * @return ResultOutputParser|Mock
     * @since 0.1.0
     */
    private function createResultOutputParserMock($verdict = true)
    {
        $mock = $this
            ->getMockFactory(Registry::ALLURE_CLI_RESULT_OUTPUT_PARSER_CLASS)
            ->getMock();
        $mock
            ->expects($this->any())
            ->method('isSuccessfulRun')
            ->willReturn($verdict);
        return $mock;
    }

    // tests

    /**
     * Tests regular run.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNormalRun()
    {
        $commandStorage = null;
        $exitCode = 0;
        $runMock = $this->createRunMock($exitCode);
        $executable = 'allure';
        $reportPath = '/tmp/report';
        $reportVersion = '9.9.9';
        $sources = array('source-a', 'source-b',);
        $expectedCommand = sprintf(
            '%s generate --report-path %s --report-version %s -- %s',
            $executable,
            $reportPath,
            $reportVersion,
            implode(' ', $sources)
        );
        
        $factory = function ($command) use ($runMock, &$commandStorage) {
            $commandStorage = $command;
            return $runMock;
        };
        
        $runFactory = $this->createPreparedPreparedRunFactoryMock($factory);
        $instance = $this->createTestInstance($runFactory);
        $runOptions = new RunOptions;
        $runOptions->setReportPath($reportPath);
        $runOptions->setReportVersion($reportVersion);
        $runOptions->setSources($sources);
        $result = $instance->run($executable, $runOptions);
        $this->assertSame(Report::STATUS_SUCCESS, $result->getStatus());
        $this->assertSame($exitCode, $result->getExitCode());
        $this->assertSame($expectedCommand, $commandStorage);
    }

    /**
     * Test exception-terminated run.
     *
     * @return void
     * @since 0.1.0
     */
    public function testExceptionalRun()
    {
        $exception = new Exception;
        $runMock = $this->createExceptionalRunMock($exception);
        $runOptions = new RunOptions;
        $runOptions->setReportVersion('1.9.9');
        $runOptions->setReportPath('/tmp/report');
        $runOptions->setSources(array('/tmp/sources',));
        
        $runFactory = $this->createRunFactoryMock($runMock);
        $instance = $this->createTestInstance($runFactory);
        
        $report = $instance->run('allure', $runOptions);
        $this->assertSame(Report::STATUS_HALTED, $report->getStatus());
        $this->assertSame($exception, $report->getException());
    }

    /**
     * Tests run that didn't end good.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingPostCheckRun()
    {
        $resultOutputParser = $this->createResultOutputParserMock(false);
        $instance = $this->createTestInstance(null, null, $resultOutputParser);
        $runOptions = new RunOptions;
        $runOptions->setReportVersion('1.9.9.');
        $runOptions->setReportPath('/tmp/report');
        $runOptions->setSources(array('/tmp/sources',));
        
        $report = $instance->run('allure', $runOptions);
        $this->assertSame(Report::STATUS_FAIL, $report->getStatus());
    }

    /**
     * Tests run that ended with non-zero exit code.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNonZeroExitCodeRun()
    {
        $resultOutputParser = $this->createResultOutputParserMock(true);
        $run = $this->createRunMock(127);
        $runFactory = $this->createRunFactoryMock($run);
        $instance
            = $this->createTestInstance($runFactory, null, $resultOutputParser);
        $runOptions = new RunOptions;
        $runOptions->setReportVersion('1.9.9.');
        $runOptions->setReportPath('/tmp/report');
        $runOptions->setSources(array('/tmp/sources',));

        $report = $instance->run('allure', $runOptions);
        $this->assertSame(Report::STATUS_FAIL, $report->getStatus());
        $this->assertNotSame(0, $report->getExitCode());
    }
}
