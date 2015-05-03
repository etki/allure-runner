<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder;
use Etki\Testing\AllureFramework\Runner\AllureCli\Run;
use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Solely formal test that ensures runner is calling right methods.
 *
 * @method Runner createTestInstance(RunFactory $runFactory, CommandBuilder $commandBuilder)
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
    const RUNNER_FACTORY_CLASS
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
     * Run options business object class name.
     *
     * @since 0.1.0
     */
    const RUN_OPTIONS_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions';
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
    
    // tests

    /**
     * As it has been said, it's a very formal test that tests actually nothing.
     *
     * @return void
     * @since 0.1.0
     */
    public function testRunnerCallbacks()
    {
        $command = 'dummy';
        $exitCode = 0;
        $receivedCommand = null;
        /** @type RunFactory|Mock $runFactory */
        $runFactory = $this
            ->getMockFactory(self::RUNNER_FACTORY_CLASS)
            ->getConstructorlessMock();
        /** @type CommandBuilder|Mock $commandBuilder */
        $commandBuilder = $this
            ->getMockFactory(self::COMMAND_BUILDER_CLASS)
            ->getConstructorlessMock();
        /** @type Run|Mock $run */
        $run = $this->getMockFactory(self::RUN_CLASS)->getConstructorlessMock();
        /** @type RunOptions|Mock $runOptions */
        $runOptions = $this->getMockFactory(self::RUN_OPTIONS_CLASS)->getMock();
        $runOptions
            ->expects($this->any())
            ->method('getSources')
            ->willReturn(array());
        $run
            ->expects($this->atLeastOnce())
            ->method('run')
            ->willReturn($exitCode);
        $runFactory
            ->expects($this->atLeastOnce())
            ->method('getRun')
            ->willReturnCallback(
                function ($input) use ($run, &$receivedCommand) {
                    $receivedCommand = $input;
                    return $run;
                }
            );
        $commandBuilder
            ->expects($this->any())
            ->method('buildGenerateCommand')
            ->willReturn($command);
        $runner = $this->createTestInstance($runFactory, $commandBuilder);
        $this->assertSame($exitCode, $runner->run($command, $runOptions));
        $this->assertSame($command, $receivedCommand);
    }
}
