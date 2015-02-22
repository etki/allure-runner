<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\IO\Controller;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\Controller\ConsoleIOController;
use Codeception\TestCase\Test;
use Etki\Testing\AllureFramework\Runner\IO\WriterInterface;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use ReflectionObject;

/**
 * Tests basic I\O controller implementation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\IO
 * @author  Etki <etki@etki.name>
 */
class ConsoleIOControllerTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\IO\Controller\ConsoleIOController';
    /**
     * Writer interface FQCN.
     *
     * @since 0.1.0
     */
    const WRITER_INTERFACE
        = '\Etki\Testing\AllureFramework\Runner\IO\WriterInterface';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility methods

    /**
     * Creates writer mock.
     *
     * @return Mock|WriterInterface
     * @since 0.1.0
     */
    private function createWriterMock()
    {
        $output = '';
        $mock = $this->getMock(
            self::WRITER_INTERFACE,
            array('write', 'getOutput', 'flushOutput',)
        );
        $mock->expects($this->any())
            ->method('write')
            ->willReturnCallback(
                function ($message) use (&$output) {
                    $output .= $message;
                }
            );
        $mock->expects($this->any())
            ->method('getOutput')
            ->willReturnCallback(
                function () use (&$output) {
                    return $output;
                }
            );
        $mock->expects($this->any())
            ->method('flushOutput')
            ->willReturnCallback(
                function () use (&$output) {
                    $copy = $output;
                    $output = '';
                    return $copy;
                }
            );
        return $mock;
    }
    
    /**
     * Returns new tested class instance.
     *
     * @param string $verbosity Ver
     *
     * @return ConsoleIOController
     * @since 0.1.0
     */
    private function createTestInstance($verbosity = Verbosity::LEVEL_INFO)
    {
        $class = self::TESTED_CLASS;
        /** @type ConsoleIOController $instance */
        $instance = new $class($this->createWriterMock(), $verbosity);
        $instance->setPrefixFormat('');
        return $instance;
    }

    /**
     * Fetches real output out of controller's writer.
     *
     * @param ConsoleIOController $controller Controller to examine.
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @return string
     * @since 0.1.0
     */
    private function getOutput(ConsoleIOController $controller)
    {
        $reflection = new ReflectionObject($controller);
        $writer = $reflection->getProperty('writer');
        $writer->setAccessible(true);
        return $writer->getValue($controller)->getOutput();
    }

    /**
     * Flushes controller's writer output and returns it's contents.
     *
     * @param ConsoleIOController $controller Controller to examine.
     *
     * @return string
     * @since 0.1.0
     */
    private function flushOutput(ConsoleIOController $controller)
    {
        $reflection = new ReflectionObject($controller);
        $writer = $reflection->getProperty('writer');
        $writer->setAccessible(true);
        return $writer->getValue($controller)->flushOutput();
    }

    // tests

    /**
     * Tests basic write methods.
     *
     * @return void
     * @since 0.1.0
     */
    public function testWriteMethods()
    {
        $output = str_replace(
            '%s',
            PHP_EOL,
            'Processing logs... Done!%sProcessing profiles... Done!%s%s' .
            'All done!%s'
        );
        //$this->expectOutputString($output);
        
        $controller = $this->createTestInstance();
        $controller->write('Processing logs...');
        $controller->write(' ');
        $controller->writeLine('Done!');
        $controller->write('Processing profiles...');
        $controller->write(' ');
        $controller->writeLine('Done!');
        $controller->writeLine();
        $controller->writeLine('All done!');
        $this->assertSame($this->flushOutput($controller), $output);
    }

    /**
     * Tests different verbosity behavior.
     *
     * @return void
     * @since 0.1.0
     */
    public function testVerbosity()
    {
        $controller = $this->createTestInstance();
        
        $controller->setVerbosity(Verbosity::LEVEL_MUTE);
        $controller->write('[controller:mute;message:info]');
        $this->assertEmpty($this->flushOutput($controller));
        
        $controller->setVerbosity(Verbosity::LEVEL_DEBUG);
        $controller->write('[controller:debug;message:info]');
        $this->assertSame(
            '[controller:debug;message:info]',
            $this->flushOutput($controller)
        );
        
        $controller->setVerbosity(Verbosity::LEVEL_INFO);
        $controller->write('[controller:info;message:info]');
        $this->assertSame(
            '[controller:info;message:info]',
            $this->flushOutput($controller)
        );
        
        $verbosity = Verbosity::LEVEL_DEBUG;
        $controller->writeLine('[controller:info;message:debug]', $verbosity);
        $this->assertEmpty($this->flushOutput($controller));
    }

    /**
     * Tests prefix.
     *
     * @return void
     * @since 0.1.0
     */
    public function testPrefixing()
    {
        $controller = $this->createTestInstance(Verbosity::LEVEL_DEBUG);
        $controller->setPrefixFormat('{dateTime} {date} {time} {verbosity}');
        $controller->write('', Verbosity::LEVEL_DEBUG);
        $this->assertStringMatchesFormat(
            '%d-%d-%d %d:%d:%d %d-%d-%d %d:%d:%d DEBUG%w',
            $this->flushOutput($controller)
        );
    }
}
