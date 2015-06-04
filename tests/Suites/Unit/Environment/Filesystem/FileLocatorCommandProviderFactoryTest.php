<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorCommandProviderFactory;
use Etki\Testing\AllureFramework\Runner\Environment\Runtime;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use UnitTester;

/**
 * Another formal test.
 *
 * @method FileLocatorCommandProviderFactory createTestInstance(Runtime $runtime)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class FileLocatorCommandProviderFactoryTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorCommandProviderFactory';
    /**
     * Runtime FQCN.
     *
     * @since 0.1.0
     */
    const RUNTIME_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\Runtime';
    /**
     * Executable location command provider interface.
     *
     * @since 0.1.0
     */
    const EXECUTABLE_LOCATION_COMMAND_PROVIDER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandProviderInterface';
    /**
     * File location command provider interface.
     *
     * @since 0.1.0
     */
    const FILE_LOCATION_COMMAND_PROVIDER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandProviderInterface';
    /**
     * Unsupported OS name.
     *
     * @since 0.1.0
     */
    const UNSUPPORTED_OS = 'BolgenOS';
    /**
     * Tester instance.
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
     * Creates runtime mockintoshé.
     *
     * @param string $osFamily OS family to return.
     *
     * @return Runtime|Mock
     * @since 0.1.0
     */
    private function createRuntimeMock($osFamily = null)
    {
        /** @type Runtime|Mock $runtimeMock */
        $runtimeMock = $this
            ->getMockFactory(self::RUNTIME_CLASS)
            ->getConstructorlessMock();
        if ($osFamily) {
            $runtimeMock
                ->expects($this->any())
                ->method('getOsFamily')
                ->willReturn($osFamily);
        }
        return $runtimeMock;
    }

    // data providers
    
    /**
     * Provides OS family name for dependent tests.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function osProvider()
    {
        return array(
            array(Runtime::FAMILY_LINUX,),
            array(Runtime::FAMILY_MAC,),
            array(Runtime::FAMILY_UNIX,),
            array(Runtime::FAMILY_WINDOWS,),
        );
    }
    
    // tests

    /**
     * Verifies that factory provides valid interface realizations on known OS
     * families.
     *
     * @param string $osFamily OS family to be returned by runtime class.
     *
     * @dataProvider osProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testExecutableLocationCommandTemplateProvision($osFamily)
    {
        $runtimeMock = $this->createRuntimeMock($osFamily);
        $instance = $this->createTestInstance($runtimeMock);
        $this->assertInstanceOf(
            self::EXECUTABLE_LOCATION_COMMAND_PROVIDER_INTERFACE,
            $instance->getExecutableLocationCommandTemplatesProvider()
        );
        $this->assertInstanceOf(
            self::FILE_LOCATION_COMMAND_PROVIDER_INTERFACE,
            $instance->getFileLocationCommandTemplatesProvider()
        );
    }


    /**
     * Verifies that factory will fail properly on unsupported OS.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\RuntimeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testExecutableCommandTemplateProvisionFailure()
    {
        $runtimeMock = $this->createRuntimeMock(self::UNSUPPORTED_OS);
        $instance = $this->createTestInstance($runtimeMock);
        $instance->getExecutableLocationCommandTemplatesProvider();
    }

    /**
     * Verifies that factory will fail properly on unsupported OS.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\RuntimeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testFileCommandTemplateProvisionFailure()
    {
        $runtimeMock = $this->createRuntimeMock(self::UNSUPPORTED_OS);
        $instance = $this->createTestInstance($runtimeMock);
        $instance->getFileLocationCommandTemplatesProvider();
    }
}
