<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment;

use Etki\Testing\AllureFramework\Runner\Environment\CommandBuilder;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests command builder.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment
 * @author  Etki <etki@etki.name>
 */
class CommandBuilderTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\CommandBuilder';
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
     * @return CommandBuilder
     * @since 0.1.0
     */
    private function createInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }
    
    // data providers

    /**
     * Data provider for generate command assembly.
     *
     * @return array
     * @since 0.1.0
     */
    public function generateCommandDataProvider()
    {
        return array(
            array(
                '/usr/bin/java -jar /home/dummy/allure.jar',
                array(
                    '/var/www/oldskool/reports',
                    '/var/www/oldskool/tests/report-data'
                ),
                '/tmp',
                '1.4.9',
                implode(
                    ' ',
                    array(
                        '/usr/bin/java -jar /home/dummy/allure.jar',
                        'generate',
                        '--report-path /tmp',
                        '--report-version 1.4.9',
                        '--',
                        '/var/www/oldskool/reports',
                        '/var/www/oldskool/tests/report-data',
                    )
                ),
            ),
            array(
                'allure.bat',
                array('D:/Projects/Oldskool/tests/report-data',),
                'C:\Temp',
                '1.4.9',
                implode(
                    ' ',
                    array(
                        'allure.bat',
                        'generate',
                        '--report-path C:\Temp',
                        '--report-version 1.4.9',
                        '--',
                        'D:/Projects/Oldskool/tests/report-data',
                    )
                ),
            )
        );
    }
    
    // tests

    /**
     * Tests generate command build.
     *
     * @param string   $executable       Executable to run.
     * @param string[] $sources          Sources list.
     * @param string   $outputDirectory  Output directory.
     * @param string   $reportVersion    Report version to use.
     * @param string   $expectedOutput   Expected result.
     *
     * @dataProvider generateCommandDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testGenerateCommandBuild(
        $executable,
        array $sources,
        $outputDirectory,
        $reportVersion,
        $expectedOutput
    ) {
        $builder = $this->createInstance();
        $command = $builder->buildGenerateCommand(
            $executable,
            $sources,
            $outputDirectory,
            $reportVersion
        );
        $this->assertSame($expectedOutput, $command);
    }
}
