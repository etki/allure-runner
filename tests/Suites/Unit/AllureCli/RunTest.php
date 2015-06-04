<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\Run;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * This class tests Allure run wrapper class. As Run delegated it's features to
 * other classes, test has become very formal and blackbox'ish.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli
 * @author  Etki <etki@etki.name>
 */
class RunTest extends AbstractClassAwareTest
{
    /**
     * Tested subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS = 'Etki\Testing\AllureFramework\Runner\AllureCli\Run';
    /**
     * Symfony process FQCN.
     *
     * @since 0.1.0
     */
    const SYMFONY_PROCESS_CLASS = 'Symfony\Component\Process\Process';
    /**
     * Output bridge FQCN.
     *
     * @since 0.1.0
     */
    const OUTPUT_BRIDGE_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\OutputBridge';
    /**
     * PHP API FQCN.
     *
     * @since 0.1.0
     */
    const PHP_API_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi';
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
     * Creates prepared test instance.
     *
     * @param int    $processExitCode Exit code that will be returned by
     *                                underlying process (Allure run).
     *
     * @param string $processOutput   Process string output.
     * @param int    $startTime       Process starting time.
     * @param int    $endTime         Process ending time.
     *
     * @return Run
     * @since 0.1.0
     */
    protected function createTestInstance(
        $processExitCode,
        $processOutput,
        $startTime = 0,
        $endTime = 1
    ) {
        $processMock = $this
            ->getMockFactory(self::SYMFONY_PROCESS_CLASS)
            ->getConstructorlessMock();
        $processMock
            ->expects($this->atLeastOnce())
            ->method('run')
            ->willReturn($processExitCode);
        $processMock
            ->expects($this->atLeastOnce())
            ->method('getExitCode')
            ->willReturn($processExitCode);
        $bridgeMock = $this
            ->getMockFactory(self::OUTPUT_BRIDGE_CLASS)
            ->getConstructorlessMock();
        $bridgeMock
            ->expects($this->atLeastOnce())
            ->method('getOutput')
            ->willReturn($processOutput);
        $phpApiMock = $this
            ->getMockFactory(self::PHP_API_CLASS)
            ->getMock();
        $phpApiMock
            ->expects($this->any())
            ->method('getTime')
            ->willReturnOnConsecutiveCalls($startTime, $endTime);
        $instance = parent::createTestInstance(
            $processMock,
            $bridgeMock,
            $phpApiMock
        );
        return $instance;
    }
    
    // data providers

    /**
     * Generic data provider.
     *
     * @return array
     * @since 0.1.0
     */
    public function runDataProvider()
    {
        return array(
            // exit code, output, start time, end time
            array(0, '', 100.0, 101.0,),
            array(100, 'dummy', 100.0, 101.0,),
            array(100, 'dummy',  100.0, 101.0,),
        );
    }
    
    // tests

    /**
     * Tests Allure run wrapper.
     *
     * @param int    $processExitCode Process exit code.
     * @param string $processOutput   Process output.
     * @param float  $startTime       Starting time.
     * @param float  $endTime         Ending time.
     *
     * @dataProvider runDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testRun(
        $processExitCode,
        $processOutput,
        $startTime,
        $endTime
    ) {
        $instance = $this->createTestInstance(
            $processExitCode,
            $processOutput,
            $startTime,
            $endTime
        );
        $instance->run();
        $this->assertSame($instance->getStartTime(), $startTime);
        $this->assertSame($instance->getEndTime(), $endTime);
        $this->assertSame($instance->getRunningTime(), $endTime - $startTime);
        $this->assertSame($instance->getOutput(), $processOutput);
        $this->assertSame($instance->getExitCode(), $processExitCode);
    }
}
