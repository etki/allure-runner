<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter;
use Symfony\Component\Yaml\Yaml;
use Codeception\Configuration;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests formatter for Allure CLI output.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli
 * @author  Etki <etki@etki.name>
 */
class OutputFormatterTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\AllureCli\OutputFormatter';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility

    /**
     * Creates Allure output formatter test instance.
     *
     * @return OutputFormatter
     * @since 0.1.0
     */
    private function getTestInstance()
    {
        // look, ma, no self::
        $class = $this::TESTED_CLASS;
        return new $class;
    }

    /**
     * Retrieves single data sample by it's identifier.
     *
     * @param string $id Identifier.
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     *
     * @return array
     * @since 0.1.0
     */
    private function getDataSample($id)
    {
        $prefix = Configuration::dataDir() . 'Samples/Allure/Output/';
        $raw = $prefix . sprintf('output.%s.raw', $id);
        $split = $prefix . sprintf('output.%s.split.yml', $id);
        return array(
            'raw' => file_get_contents($raw),
            'split' => Yaml::parse(file_get_contents($split)),
        );
    }
    
    // data providers

    /**
     * Provides data for splitting test.
     *
     * @return array
     * @since 0.1.0
     */
    public function splittingTestDataProvider()
    {
        $data = array();
        for ($i = 1; $i < 2; $i++) {
            $data[] = $this->getDataSample($i);
        }
        return $data;
    }

    /**
     * Provides data for complete formatting test.
     *
     * @return array
     * @since 0.1.0
     */
    public function fullBlownFormattingDataProvider()
    {
        return array(
            array(
                'Successfully generated report',
                array('(Allure/stderr) Successfully generated report',),
                'stderr',
                '({software}/{stream})',
                'Allure',
            ),
            array(
                sprintf('Dummy line%sDummy line', PHP_EOL),
                array(
                    '[Allure CLI] [err] Dummy line',
                    '[Allure CLI] [err] Dummy line',
                ),
                'err',
                '[{software}] [{stream}]',
                'Allure CLI',
            ),
            array(
                'Dummy',
                array('Dummy'),
                'err',
                null,
                'Allure'
            )
        );
    }
    
    // tests

    /**
     * Tests how output is divided into several lines.
     *
     * @param string   $allureOutput
     * @param string[] $expectedResult
     *
     * @dataProvider splittingTestDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testOutputSplitting($allureOutput, $expectedResult)
    {
        $formatter = $this->getTestInstance();
        $formatter->setPrefixFormat(null);
        $this->assertSame(
            $formatter->formatOutput($allureOutput, 'ERR'),
            $expectedResult
        );
    }

    /**
     * Tests completely formatted string.
     *
     * @param string   $allureOutput   Raw output.
     * @param string[] $expectedResult Expected formatter return.
     * @param string   $stream         Stream name.
     * @param string   $prefixFormat   Format of the line prefix.
     * @param string   $softwareName   Name of the software to be used in output
     *                                 prefix.
     *
     * @dataProvider fullBlownFormattingDataProvider
     *
     * @SuppressWarnings(PHPMD.TooMuchArguments)
     *
     * @return void
     * @since 0.1.0
     */
    public function testFormatting(
        $allureOutput,
        $expectedResult,
        $stream = null,
        $prefixFormat = null,
        $softwareName = null
    ) {
        $formatter = $this->getTestInstance();
        $formatter->setPrefixFormat($prefixFormat);
        $formatter->setSoftwareName($softwareName);
        $this->assertSame(
            $expectedResult,
            $formatter->formatOutput($allureOutput, $stream)
        );
    }
}
