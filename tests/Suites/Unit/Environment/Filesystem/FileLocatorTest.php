<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandProviderInterface;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandProviderInterface;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorCommandProviderFactory
    as CommandProviderFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Environment\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use UnitTester;

/**
 * Formal file locator test.
 *
 * @SuppressWarnings(PHPMD.LongVariableName)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class FileLocatorTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator';
    /**
     * Process factory FQCN.
     *
     * @since 0.1.0
     */
    const PROCESS_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory';
    /**
     * Executable location command provider FQIN.
     *
     * @since 0.1.0
     */
    const EXECUTABLE_LOCATION_COMMAND_PROVIDER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandProviderInterface';
    /**
     * File location command provider FQIN.
     *
     * @since 0.1.0
     */
    const FILE_LOCATION_COMMAND_PROVIDER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandProviderInterface';
    /**
     * File locator command provider factory FQCN.
     *
     * @since 0.1.0
     */
    const FILE_LOCATOR_COMMAND_PROVIDER_FACTORY
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorCommandProviderFactory';
    /**
     * Codeception tester instance.
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
     * Retrieves process factory mock.
     *
     * @param string[] $output   Process output as separate string.
     * @param int      $exitCode Process exit code.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return ProcessFactory|Mock
     * @since 0.1.0
     */
    private function getProcessFactoryMock(array $output, $exitCode = 0)
    {
        /** @type ProcessFactoryMockFactory $processFactoryMockFactory */
        $processFactoryMockFactory =
            $this->getMockFactory(self::PROCESS_FACTORY_CLASS);
        $output = implode(PHP_EOL, $output);
        return $processFactoryMockFactory->getPreparedMock($output, $exitCode);
    }

    /**
     * Returns command template provider mock.
     *
     * @param string   $fqin             Fully-qualified interface name.
     * @param string   $method           Method name.
     * @param string[] $commandTemplates List of command templates to return.
     *
     * @return Mock
     * @since 0.1.0
     */
    private function getCommandTemplateProviderMock(
        $fqin,
        $method,
        array $commandTemplates
    ) {
        $providerMock = $this->getMockFactory($fqin)->getMock();
        $providerMock
            ->expects($this->any())
            ->method($method)
            ->willReturn($commandTemplates);
        return $providerMock;
    }

    /**
     * Returns provider mock for executable location commands.
     *
     * @param string[] $commandTemplates List of command templates mock should
     *                                   return.
     *
     * @return ExecutableLocationCommandProviderInterface|Mock
     * @since 0.1.0
     */
    private function getExecutableLocationCommandTemplateProviderMock(
        array $commandTemplates
    ) {
        $providerMock = $this->getCommandTemplateProviderMock(
            self::EXECUTABLE_LOCATION_COMMAND_PROVIDER_INTERFACE,
            'getExecutableLocationCommandTemplates',
            $commandTemplates
        );
        return $providerMock;
    }


    /**
     * Returns provider mock for file location commands.
     *
     * @param string[] $commandTemplates List of command templates mock should
     *                                   return.
     *
     * @return FileLocationCommandProviderInterface|Mock
     * @since 0.1.0
     */
    private function getFileLocationCommandTemplateProviderMock(
        array $commandTemplates
    ) {
        $providerMock = $this->getCommandTemplateProviderMock(
            self::FILE_LOCATION_COMMAND_PROVIDER_INTERFACE,
            'getFileLocationCommandTemplates',
            $commandTemplates
        );
        return $providerMock;
    }

    /**
     * Retrieves command template provider factory mock.
     *
     * @param string[] $executableLocationCommandTemplates
     * @param string[] $fileLocationCommandTemplates
     *
     * @return CommandProviderFactory|Mock
     * @since 0.1.0
     */
    private function getCommandTemplateProviderFactoryMock(
        array $executableLocationCommandTemplates = array(),
        array $fileLocationCommandTemplates = array()
    ) {
        $factoryMock = $this
            ->getMockFactory(self::FILE_LOCATOR_COMMAND_PROVIDER_FACTORY)
            ->getConstructorlessMock();
        $factoryMock
            ->expects($this->any())
            ->method('getExecutableLocationCommandTemplatesProvider')
            ->willReturn(
                $this->getExecutableLocationCommandTemplateProviderMock(
                    $executableLocationCommandTemplates
                )
            );
        $factoryMock
            ->expects($this->any())
            ->method('getFileLocationCommandTemplatesProvider')
            ->willReturn(
                $this->getFileLocationCommandTemplateProviderMock(
                    $fileLocationCommandTemplates
                )
            );
        return $factoryMock;
    }

    /**
     * Creates test instance.
     *
     * @param CommandProviderFactory $commandProviderFactory Command provider
     *                                                       factory.
     * @param ProcessFactory         $processFactory         Process factory.
     *
     * @return FileLocator
     * @since 0.1.0
     */
    protected function createTestInstance(
        CommandProviderFactory $commandProviderFactory,
        ProcessFactory $processFactory
    ) {
        $instance = parent::createTestInstance(
            $commandProviderFactory,
            $processFactory
        );
        return $instance;
    }
    
    // tests

    /**
     * Tests successful file location scenario.
     *
     * @return void
     * @since 0.1.0
     */
    public function testSuccessfulFileLocation()
    {
        $locations = array('/dummy-path/dummy', '/dummy-path/dummy-dir/dummy',);
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(
                array(),
                array('dummysearch %s',)
            ),
            $this->getProcessFactoryMock($locations)
        );
        $this->assertSame($locations, $instance->locateFile('dummy'));
    }

    /**
     * Tests successful executable location scenario.
     *
     * @return void
     * @since 0.1.0
     */
    public function testSuccessfulExecutableLocation()
    {
        $locations = array('/dummy-path/dummy', '/dummy-path/dummy-dir/dummy',);
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(
                array('dummysearch %s',)
            ),
            $this->getProcessFactoryMock($locations)
        );
        $this->assertSame($locations, $instance->locateExecutable('dummy'));
    }

    /**
     * Verifies that component returns null on empty response.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingExecutableLocation()
    {
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(
                array('dummysearch %s',)
            ),
            $this->getProcessFactoryMock(array())
        );
        $this->assertNull($instance->locateExecutable('dummy'));
    }

    /**
     * Verifies that component returns null on empty response.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingFileLocation()
    {
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(
                array(),
                array('dummysearch %s',)
            ),
            $this->getProcessFactoryMock(array())
        );
        $this->assertNull($instance->locateFile('dummy'));
    }

    /**
     * Verifies that process output is ignored if exit code is somewhat
     * different from 0.
     *
     * @return void
     * @since 0.1.0
     */
    public function testErroneousProcessHandling()
    {
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(
                array(),
                array('dummysearch %s',)
            ),
            $this->getProcessFactoryMock(array('dummy',), 1)
        );
        $this->assertNull($instance->locateFile('dummy'));
    }

    /**
     * Verifies that exception is thrown whenever no commands are provided.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\RuntimeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testExceptionalExecutableLocation()
    {
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(),
            $this->getProcessFactoryMock(array())
        );
        $instance->locateExecutable('dummy');
    }

    /**
     * Verifies that exception is thrown whenever no commands are provided.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\RuntimeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testExceptionalFileLocation()
    {
        $instance = $this->createTestInstance(
            $this->getCommandTemplateProviderFactoryMock(),
            $this->getProcessFactoryMock(array())
        );
        $instance->locateFile('dummy');
    }
}
