<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\WindowsFileLocator;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\SymfonyProcessMockFactory;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Verifies that windows file location happens as expected.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class WindowsFileLocatorTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\Filesystem\WindowsFileLocator';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    /**
     * Creates test instance.
     *
     * @param ProcessFactory $factory
     *
     * @return WindowsFileLocator
     * @since 0.1.0
     */
    public function createTestInstance(ProcessFactory $factory = null)
    {
        $class = self::TESTED_CLASS;
        if (!$factory) {
            $factoryFactory = new ProcessFactoryMockFactory;
            $factory = $factoryFactory->getMock($this);
        }
        /** @type WindowsFileLocator $instance */
        $instance = new $class($factory);
        return $instance;
    }

    /**
     * Verifies that windows locator executes expected commands and treats
     * results properly.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testWindowsExecutableLocation()
    {
        $processFactory = new SymfonyProcessMockFactory;
        $processFactoryFactory = new ProcessFactoryMockFactory;
        $output = "C:\\allure.bat\nD:\\Programs\\allure.exe";
        $processMock = $processFactory->getMock($this, 0, $output);
        $processFactoryMock
            = $processFactoryFactory->getMock($this, $processMock);
        
        $locator = $this->createTestInstance($processFactoryMock);
        $this->assertSame(
            'C:\\allure.bat',
            $locator->locateExecutable('allure')
        );
        $this->assertSame('where allure', $processMock->getCommandLine());

        $processMock = $processFactory->getMock($this, 1, '');
        $processFactoryMock
            = $processFactoryFactory->getMock($this, $processMock);
        $locator = $this->createTestInstance($processFactoryMock);
        $this->assertNull($locator->locateExecutable('allure'));
        $this->assertSame('where allure', $processMock->getCommandLine());
    }
}
