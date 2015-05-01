<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\UnixFileLocator;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\SymfonyProcessMockFactory;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests UNIX file locator.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class UnixFileLocatorTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\Filesystem\UnixFileLocator';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    // utility functions

    /**
     * Creates test instance.
     *
     * @param ProcessFactory $factory Factory to set.
     *
     * @return UnixFileLocator
     * @since 0.1.0
     */
    private function createTestInstance(ProcessFactory $factory = null)
    {
        $class = self::TESTED_CLASS;
        /** @type UnixFileLocator $instance */
        $instance = new $class($factory);
        return $instance;
    }
    
    // tests

    /**
     * Verifies that executable location calls expected commands and treats
     * result properly.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testExecutableLocation()
    {
        $processMockFactory = new SymfonyProcessMockFactory;
        $output = "/usr/bin/allure\n/usr/local/bin/allure";
        $processMock = $processMockFactory->getMock($this, 0, $output);
        $processFactoryMockFactory = new ProcessFactoryMockFactory;
        $processFactoryMock
            = $processFactoryMockFactory->getMock($this, $processMock);
        
        $locator = $this->createTestInstance($processFactoryMock);
        $this->assertSame(
            $locator->locateExecutable('allure'),
            '/usr/bin/allure'
        );
        $this->assertSame(
            'which allure',
            $processMock->getCommandLine()
        );
    }

    /**
     * Verifies that file location calls expected commands and treats result
     * properly.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testFileLocation()
    {
        $processMockFactory = new SymfonyProcessMockFactory;
        $output = "/home/user/data-file\n/var/cache/data-file";
        $processMock = $processMockFactory->getMock($this, 0, $output);
        $processFactoryMockFactory = new ProcessFactoryMockFactory;
        $processFactoryMock
            = $processFactoryMockFactory->getMock($this, $processMock);

        $locator = $this->createTestInstance($processFactoryMock);
        $this->assertSame(
            $locator->locateFile('data-file'),
            '/home/user/data-file'
        );
        $this->assertSame(
            'locate data-file',
            $processMock->getCommandLine()
        );
    }
}
