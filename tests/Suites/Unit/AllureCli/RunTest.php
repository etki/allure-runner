<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\Run;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * This class tests Allure run wrapper class.
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
     * Output parser FQCN.
     *
     * @since 0.1.0
     */
    const OUTPUT_PARSER_CLASS
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
     * @param int       $processExitCode Exit code that will be returned by
     *                                   underlying process (Allure run).
     * @param bool|null $detectionResult Result of parser success detection.
     *
     * @return Run
     * @since 0.1.0
     */
    protected function createTestInstance($processExitCode, $detectionResult)
    {
        $processMock = $this
            ->getMockFactory(self::SYMFONY_PROCESS_CLASS)
            ->getConstructorlessMock();
        $processMock
            ->expects($this->atLeastOnce())
            ->method('run')
            ->willReturn(null);
        $processMock
            ->expects($this->atLeastOnce())
            ->method('getExitCode')
            ->willReturn($processExitCode);
        $bridgeMock = $this
            ->getMockFactory(self::OUTPUT_BRIDGE_CLASS)
            ->getConstructorlessMock();
        $parserMock = $this
            ->getMockFactory(self::OUTPUT_PARSER_CLASS)
            ->getConstructorlessMock();
        $parserMock
            ->expects($this->any())
            ->method('isSuccessfulRun')
            ->willReturn($detectionResult);
        $instance = parent::createTestInstance(
            $processMock,
            $bridgeMock,
            $parserMock
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
            array(0, null, 0,),
            array(0, true, 0,),
            array(0, false, Configuration::GENERIC_ERROR_EXIT_CODE,),
            array(100, null, 100),
            array(100, false, 100),
        );
    }
    
    // tests

    /**
     * Tests Allure run wrapper.
     *
     * @param int       $processExitCode Process exit code.
     * @param bool|null $detectionResult Success detection result.
     * @param int       $expectedResult  Expected run result.
     *
     * @dataProvider runDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testRun(
        $processExitCode,
        $detectionResult,
        $expectedResult
    ) {
        $run = $this->createTestInstance($processExitCode, $detectionResult);
        $this->assertSame($expectedResult, $run->run());
    }
}
