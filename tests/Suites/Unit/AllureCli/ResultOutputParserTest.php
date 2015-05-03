<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Test for Allure CLI result output parser.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli
 * @author  Etki <etki@etki.name>
 */
class ResultOutputParserTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility

    /**
     * Creates test instance.
     *
     * @return ResultOutputParser
     * @since 0.1.0
     */
    private function createTestInstance()
    {
        $className = self::TESTED_CLASS;
        return new $className;
    }
    
    // data providers

    /**
     * Provides output results and their successful or unsuccessful meaning as a
     * boolean value.
     *
     * @return array
     * @since 0.1.0
     */
    public function resultProvider()
    {
        return array(
            array(
                'Successfully generated report to [/some/path].',
                null,
                true,
            ),
            array(
                'Required parameters are missing: Results patterns',
                null,
                false,
            ),
        );
    }

    // tests

    /**
     * Tests output parsing.
     *
     * @param string      $output         Allure CLI output.
     * @param string|null $allureVersion  Allure version.
     * @param bool        $expectedResult Expected parsing result
     *
     * @dataProvider resultProvider
     *
     * @since 0.1.0
     */
    public function testOutputParsing($output, $allureVersion, $expectedResult)
    {
        $parser = $this->createTestInstance();
        $result = $parser->isSuccessfulRun($output, $allureVersion);
        $this->assertSame($expectedResult, $result);
    }
}
