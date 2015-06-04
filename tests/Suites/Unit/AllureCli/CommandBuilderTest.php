<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\AllureCli;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * Tests command builder.
 *
 * @method CommandBuilder createTestInstance(string $executable = null, string $command = null)
 *
 * @IgnoreAnnotation(value={"expectedException"})
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment
 * @author  Etki <etki@etki.name>
 */
class CommandBuilderTest extends AbstractClassAwareTest
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder';
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
                array(
                    'executable' => '/usr/bin/java -jar /home/dummy/allure.jar',
                    'command' => 'generate',
                    'options' => array(
                        'report-path' => '/tmp',
                        'report-version' => '1.4.9',
                    ),
                    'arguments' => array(),
                    'postArguments' => array(
                        '/var/www/oldskool/reports',
                        '/var/www/oldskool/tests/report-data'
                    ),
                ),
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
                array(
                    'executable' => 'allure.bat',
                    'command' => 'generate',
                    'options' => array(
                        'report-path' => 'C:\Temp',
                        'report-version' => '1.4.9',
                    ),
                    'arguments' => array(),
                    'postArguments' => array(
                        'D:/Projects/Oldskool/tests/report-data',
                    ),
                ),
                implode(
                    ' ',
                    array(
                        'allure.bat',
                        'generate',
                        '--report-path C:\\Temp',
                        '--report-version 1.4.9',
                        '--',
                        'D:/Projects/Oldskool/tests/report-data',
                    )
                ),
            ),
            array(
                array(
                    'executable' => 'allure.bat',
                    'command' => 'generate',
                    'options' => array(
                        'report-version' => '1.4.9',
                    ),
                    'arguments' => array(),
                    'postArguments' => array(
                        'C:\\hryuchevo new',
                    ),
                ),
                implode(
                    ' ',
                    array(
                        'allure.bat',
                        'generate',
                        '--report-version 1.4.9',
                        '--',
                        '"C:\\hryuchevo new"'
                    )
                )
            ),
            array(
                array(
                    'executable' => '/usr/bin/dummy',
                    'command' => 'dummy',
                    'options' => array(),
                    'arguments' => array('up', 'down',),
                    'postArguments' => array(),
                ),
                '/usr/bin/dummy dummy up down',
            )
        );
    }
    
    // tests

    /**
     * Tests generate command build.
     *
     * @param array  $definitions    List of command build definitions.
     * @param string $expectedOutput Expected result.
     *
     * @dataProvider generateCommandDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testGenerateCommandBuild(
        array $definitions,
        $expectedOutput
    ) {
        $builder = $this->createTestInstance();
        $builder->setExecutable($definitions['executable'])
            ->setCommand($definitions['command'])
            ->addOptions($definitions['options'])
            ->addArguments($definitions['arguments'])
            ->addPostArguments($definitions['postArguments']);
        $this->assertSame($expectedOutput, $builder->getCommand());
    }
    public function testGenerateEmptyCommand()
    {
        $executable = 'fakecutable';
        $builder = $this->createTestInstance($executable);
        $this->assertSame($executable, $builder->getCommand());
    }

    /**
     * Verifies that exception will be thrown on missing executable.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\AllureCli\ExecutableNotSpecifiedException
     *
     * @return void
     * @since 0.1.0
     */
    public function testMissingExecutableReaction()
    {
        $builder = $this->createTestInstance();
        $builder
            ->setCommand('command')
            ->addArgument('dummy')
            ->addOption('dummy', 'dummy')
            ->addPostArguments(array('dummy',))
            ->getCommand();
    }
}
