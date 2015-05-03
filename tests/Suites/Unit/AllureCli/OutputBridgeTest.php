<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\OutputBridge;
use Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Symfony\Component\Process\Process;
use UnitTester;

/**
 * Another mostly formal test that went quite overcomplicated.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli
 * @author  Etki <etki@etki.name>
 */
class OutputBridgeTest extends AbstractClassAwareTest
{
    /**
     * Tested class name.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\OutputBridge';
    /**
     * I/O controller FQIN.
     */
    const IO_CONTROLLER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface';
    /**
     * Output formatter FQCN.
     *
     * @since 0.1.0
     */
    const OUTPUT_FORMATTER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility methods

    /**
     * Returns tested class.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }

    /**
     * Creates instance that is prepared for testing.
     *
     * @param null $ioControllerMock    Variable that will be populated with
     *                                  I/O controller mock
     * @param null $outputFormatterMock Variable that will be populated with
     *                                  output formatter mock.
     *
     * @return OutputBridge
     * @since 0.1.0
     */
    protected function createPreparedInstance(
        &$outputFormatterMock = null,
        &$ioControllerMock = null,
        $outputPrefix = null,
        &$formattedLines = null
    ) {
        $ioControllerMock = $this
            ->getMockFactory(self::IO_CONTROLLER_INTERFACE)
            ->getDummyMock();
        $outputFormatterMock = $this
            ->getMockFactory(self::OUTPUT_FORMATTER_CLASS)
            ->getMock();
        $outputFormatterMock
            ->expects($this->atLeastOnce())
            ->method('formatOutput')
            ->willReturnCallback(
                function ($input, $stream) use ($outputPrefix) {
                    $lines = array_map('rtrim', explode("\n", $input));
                    $callback = function ($line) use ($outputPrefix, $stream) {
                        $format = '%s/%s:%s';
                        return sprintf($format, $outputPrefix, $stream, $line);
                    };
                    $lines = array_map($callback, array_filter($lines));
                    return $lines;
                }
            );
        $ioControllerMock
            ->expects($this->atLeastOnce())
            ->method('writeLines')
            ->willReturnCallback(
                function ($lines) use (&$formattedLines) {
                    $formattedLines = $lines;
                }
            );
        $instance = $this->createTestInstance(
            $outputFormatterMock,
            $ioControllerMock
        );
        return $instance;
    }
    
    // data providers

    /**
     * Provides most complete samples as possible.
     *
     * @return array
     * @since 0.1.0
     */
    public function completeDataProvider()
    {
        return array(
            array(
                str_replace('%s', PHP_EOL, 'line-1%sline-2%sline-3%s'),
                Process::ERR,
                'prefix',
            )
        );
    }
    
    // tests

    /**
     * Tests real bridging.
     *
     * @param string $input  Pseudo Allure output that is fed as bridge input.
     * @param string $stream Stream name
     * @param string $prefix Output prefix for output formatter.
     *
     * @dataProvider completeDataProvider
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return void
     * @since 0.1.0
     */
    public function testBridging($input, $stream, $prefix)
    {
        /** @type OutputFormatter $outputFormatter */
        $bridge = $this->createPreparedInstance(
            $outputFormatter,
            $ioController,
            $prefix,
            $formattedLines
        );
        $bridge($stream, $input);
        $this->assertSame($input, $bridge->getOutput());
        $this->assertSame(
            $outputFormatter->formatOutput($input, $stream),
            $formattedLines
        );
    }

    /**
     * Tests that output handling is done as expected.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return void
     * @since 0.1.0
     */
    public function testOutputHandling()
    {
        $prefix = 'prefix';
        $stream = 'fake-stream';
        $input = sprintf('fake-input%sfake-input', PHP_EOL);
        $bridge
            = $this->createPreparedInstance($formatter, $ioController, $prefix);
        $bridge($stream, $input);
        $this->assertSame($input, $bridge->getOutput());
        $bridge->flushOutput();
        $this->assertEmpty($bridge->getOutput());
    }
}
