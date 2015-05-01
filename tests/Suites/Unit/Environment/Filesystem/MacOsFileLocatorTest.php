<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\MacOsFileLocator;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\SymfonyProcessMockFactory;
use Symfony\Component\Process\Process;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests Mac OS file locator class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class MacOsFileLocatorTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\Filesystem\MacOsFileLocator';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    // utility methods

    /**
     * Generates test instance.
     *
     * @param ProcessFactory $factory Process factory or it's mock.
     *
     * @return MacOsFileLocator
     * @since 0.1.0
     */
    private function createTestInstance(ProcessFactory $factory = null)
    {
        $class = self::TESTED_CLASS;
        if (!$factory) {
            $factoryFactory = new ProcessFactoryMockFactory;
            $factory = $factoryFactory->getMock($this);
        }
        return new $class($factory);
    }

    /**
     * Returns process that simulates successful search.
     *
     * @return Mock|Process
     * @since 0.1.0
     */
    private function getSuccessfulProcessMock()
    {
        $processMockFactory = new SymfonyProcessMockFactory;
        $processMock = $processMockFactory->getMock($this);
        $processMock->expects($this->any())->method('run')->willReturn(0);
        $processMock
            ->expects($this->any())
            ->method('getExitCode')
            ->willReturn(0);
        $processMock
            ->expects($this->any())
            ->method('getOutput')
            ->willReturn("/bin/allure\n/home/user/binaries/allure");
        return $processMock;
    }

    /**
     * Returns process that simulates it hasn't found anything.
     *
     * @return Mock|Process
     * @since 0.1.0
     */
    private function getFailingProcessMock()
    {
        $processMockFactory = new SymfonyProcessMockFactory;
        $failingProcessMock = $processMockFactory->getMock($this);
        $failingProcessMock
            ->expects($this->any())
            ->method('getOutput')
            ->willReturn('');
        $failingProcessMock
            ->expects($this->any())
            ->method('run')
            ->willReturn(1);
        $failingProcessMock
            ->expects($this->any())
            ->method('getExitCode')
            ->willReturn(1);
        return $failingProcessMock;
    }
    
    // tests

    /**
     * Verifies that extended file location works as expected.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testFileLocation()
    {
        
        $processFactoryMockFactory = new ProcessFactoryMockFactory;
        $successfulProcessMock = $this->getSuccessfulProcessMock();
        $failingProcessMock = $this->getFailingProcessMock();
        $processFactoryMock = $processFactoryMockFactory->getMock($this);
        $callback = function ($command) use (
            $successfulProcessMock,
            $failingProcessMock
        ) {
            if (strpos($command, 'which') !== false
                || strpos($command, 'locate') !== false
            ) {
                $failingProcessMock->setCommandLine($command);
                return $failingProcessMock;
            }
            $successfulProcessMock->setCommandLine($command);
            return $successfulProcessMock;
        };
        $processFactoryMock
            ->expects($this->any())
            ->method('getProcess')
            ->willReturnCallback($callback);
        
        $locator = $this->createTestInstance($processFactoryMock);
        
        $this->assertSame(
            '/bin/allure',
            $locator->locateFile('allure')
        );
        
        $this->assertSame(
            'mdfind allure',
            $successfulProcessMock->getCommandLine()
        );
    }
}
