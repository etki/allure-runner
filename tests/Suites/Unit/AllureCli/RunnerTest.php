<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder;
use Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilderFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser;
use Etki\Testing\AllureFramework\Runner\AllureCli\Run;
use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Exception;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Solely formal test that ensures runner is calling right methods.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli
 * @author  Etki <etki@etki.name>
 */
class RunnerTest extends AbstractClassAwareTest
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS = 'Etki\Testing\AllureFramework\Runner\AllureCli\Runner';
    /**
     * Runner factory FQCN.
     *
     * @since 0.1.0
     */
    const RUN_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory';
    /**
     * Runner class FQCN.
     *
     * @since 0.1.0
     */
    const RUN_CLASS = 'Etki\Testing\AllureFramework\Runner\AllureCli\Run';
    /**
     * Command builder class name.
     *
     * @since 0.1.0
     */
    const COMMAND_BUILDER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder';
    /**
     * Command builder factory FQCN.
     *
     * @since 0.1.0
     */
    const COMMAND_BUILDER_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilderFactory';
    /**
     * Run options business object class name.
     *
     * @since 0.1.0
     */
    const RUN_OPTIONS_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions';
    /**
     * Allure output parser FQCN.
     *
     * @since 0.1.0
     */
    const RESULT_OUTPUT_PARSER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility methods

    /**
     * Returns tested class name.
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
     * @param RunFactory         $runFactory
     * @param CommandBuilder     $commandBuilder
     * @param ResultOutputParser $outputParser
     *
     * @return Runner
     * @since 0.1.0
     */
    public function createTestInstance(
        RunFactory $runFactory = null,
        CommandBuilder $commandBuilder = null,
        ResultOutputParser $outputParser = null
    ) {
        $commandBuilder = $commandBuilder ?: $this->createCommandBuilderMock();
        $instance = parent::createTestInstance(
            $runFactory ?: $this->createRunFactoryMock(),
            $this->createCommandBuilderFactoryMock($commandBuilder),
            $outputParser ?: $this->createOutputParserMock()
        );
        return $instance;
    }

    /**
     * Creates builder command.
     *
     * @param string $builtCommand Built command.
     *
     * @return CommandBuilder|Mock
     * @since 0.1.0
     */
    private function createCommandBuilderMock($builtCommand = null)
    {
        $commandBuilder = $this
            ->getMockFactory(self::COMMAND_BUILDER_CLASS)
            ->getMock();
        $setters = array(
            'setCommand',
            'setExecutable',
            'addOption',
            'addOptionValues',
            'addOptions',
            'addArgument',
            'addArguments',
            'addPostArgument',
            'addPostArguments',
        );
        foreach ($setters as $method) {
            $commandBuilder
                ->expects($this->any())
                ->method($method)
                ->withAnyParameters()
                ->willReturn($commandBuilder);
        }
        $commandBuilder
            ->expects($this->any())
            ->method('getCommand')
            ->willReturn($builtCommand);
        return $commandBuilder;
    }

    /**
     * Creates prepared test instance.
     *
     * @param int       $exitCode       Process exit code.
     * @param bool|null $parserResponse Output parser response.
     * @param Exception $exception      Exception thrown during run.
     *
     * @return Runner
     * @since 0.1.0
     */
    private function createPreparedTestInstance(
        $exitCode,
        $parserResponse,
        $exception = null
    ) {
        $run = $this
            ->getMockFactory(self::RUN_CLASS)
            ->getConstructorlessMock();
        $run
            ->expects($this->any())
            ->method('getExitCode')
            ->willReturn($exitCode);
        if ($exception) {
            $run
                ->expects($this->any())
                ->method('run')
                ->willThrowException($exception);
        }
        $runFactory = $this->createRunFactoryMock($run);
        $outputParser = $this->createOutputParserMock($parserResponse);
        return $this->createTestInstance($runFactory, null, $outputParser);
    }

    /**
     * Creates mock of run factory.
     *
     * @param Run $run Run to inject.
     *
     * @return RunFactory|Mock
     * @since 0.1.0
     */
    private function createRunFactoryMock(Run $run = null)
    {
        if (!$run) {
            $run = $this
                ->getMockFactory(self::RUN_CLASS)
                ->getConstructorlessMock();
        }
        $runFactory = $this
            ->getMockFactory(self::RUN_FACTORY_CLASS)
            ->getConstructorlessMock();
        $runFactory
            ->expects($this->any())
            ->method('getRun')
            ->willReturn($run);
        return $runFactory;
    }

    /**
     * Creates mock of output parser.
     *
     * @param null|bool $response Success detection response.
     *
     * @return ResultOutputParser|Mock
     * @since 0.1.0
     */
    private function createOutputParserMock($response = null)
    {
        $outputParser = $this
            ->getMockFactory(self::RESULT_OUTPUT_PARSER_CLASS)
            ->getMock();
        $outputParser
            ->expects($this->any())
            ->method('isSuccessFulRun')
            ->willReturn($response);
        return $outputParser;
    }

    /**
     * Creates command builder factory mock.
     *
     * @param CommandBuilder $commandBuilder Command builder to return on
     *                                       `getCommandBuilder()` call.
     *
     * @return CommandBuilderFactory|Mock
     * @since 0.1.0
     */
    private function createCommandBuilderFactoryMock(
        CommandBuilder $commandBuilder
    ) {
        $mock = $this
            ->getMockFactory(self::COMMAND_BUILDER_FACTORY_CLASS)
            ->getMock();
        $mock
            ->expects($this->any())
            ->method('getCommandBuilder')
            ->willReturn($commandBuilder);
        return $mock;
    }

    /**
     * Returns run options mock.
     *
     * @return RunOptions|Mock
     * @since 0.1.0
     */
    private function createRunOptionsMock()
    {
        $mock = $this->getMockFactory(self::RUN_OPTIONS_CLASS)->getMock();
        $mock
            ->expects($this->any())
            ->method('getSources')
            ->willReturn(array());
        return $mock;
    }
    
    // data providers

    /**
     * Provides data for run emulation.
     *
     * @return array
     * @since 0.1.0
     */
    public function runnerDataProvider()
    {
        return array(
            array(0, true, null, Report::STATUS_SUCCESS,),
            array(0, null, null, Report::STATUS_SUCCESS,),
            array(0, false, null, Report::STATUS_FAIL,),
            array(0, true, new Exception, Report::STATUS_HALTED,),
        );
    }
    
    // tests

    /**
     * As it has been said, it's a very formal test that tests actually nothing.
     *
     * @param int            $exitCode       Exit code.
     * @param null|bool      $parserResponse What parser will return when
     *                                       detecting success.
     * @param Exception|null $exception      Exception to be thrown during run.
     * @param string         $expectedStatus Expected report status.
     *
     * @dataProvider runnerDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testRunner(
        $exitCode,
        $parserResponse,
        $exception,
        $expectedStatus
    ) {
        $runner = $this->createPreparedTestInstance(
            $exitCode,
            $parserResponse,
            $exception
        );
        $runOptions = $this->createRunOptionsMock();
        /** @type Report $report */
        $report = $runner->run('dummy', $runOptions);
        $this->assertSame($expectedStatus, $report->getStatus());
        $this->assertSame($exception, $report->getException());
        $this->assertSame($exitCode, $report->getExitCode());
    }
}
