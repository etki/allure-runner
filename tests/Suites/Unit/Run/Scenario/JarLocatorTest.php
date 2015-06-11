<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarLocator;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests `.jar` file locator.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarLocatorTest extends AbstractClassAwareTest
{
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
        return Registry::JAR_LOCATOR_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param FileLocator $fileLocator File locator instance.
     *
     * @return JarLocator
     * @since 0.1.0
     */
    protected function createTestInstance(FileLocator $fileLocator = null)
    {
        $ioController = $this
            ->getMockFactory(Registry::IO_CONTROLLER_INTERFACE)
            ->getMock();
        return parent::createTestInstance($fileLocator, $ioController);
    }

    /**
     * Creates file locator mock.
     *
     * @param bool $success Whether file locator should find smth or not.
     *
     * @return Mock|FileLocator
     * @since 0.1.0
     */
    private function createFileLocatorMock($success = true)
    {
        $mock = $this
            ->getMockFactory(Registry::FILE_LOCATOR_CLASS)
            ->getConstructorlessMock();
        $matcher = $mock
            ->expects($this->any())
            ->method('locateFile');
        if ($success) {
            $matcher->willReturnCallback(
                function ($argument) {
                    return array($argument,);
                }
            );
        } else {
            $matcher->willReturn(null);
        }
        return $mock;
    }

    // tests

    /**
     * Tests success scenario.
     *
     * @return void
     * @since 0.1.0
     */
    public function testSuccess()
    {
        $fileLocator = $this->createFileLocatorMock(true);
        $instance = $this->createTestInstance($fileLocator);
        $this->assertNotNull($instance->getJar());
    }

    /**
     * Tests failure scenario.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailure()
    {
        $fileLocator = $this->createFileLocatorMock(false);
        $instance = $this->createTestInstance($fileLocator);
        $this->assertNull($instance->getJar());
    }
}
