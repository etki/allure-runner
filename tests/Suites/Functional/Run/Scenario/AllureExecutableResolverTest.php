<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Run\Scenario\AllureExecutableResolver;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarResolver;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JavaExecutableLocator;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Closure;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests Allure executable resolver under different conditions,
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class AllureExecutableResolverTest extends AbstractClassAwareTest
{
    /**
     * Tester instance.
     *
     * @type FunctionalTester
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
        return Registry::ALLURE_EXECUTABLE_RESOLVER_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param Configuration         $configuration    Configuration instance.
     * @param FileLocator           $fileLocator      File locator instance.
     * @param JavaExecutableLocator $javaLocator      Java executable locator
     *                                                instance.
     * @param JarResolver           $jarResolver      Jar resolver instance.
     * @param Filesystem            $filesystemHelper Filesystem helper.
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @return AllureExecutableResolver
     * @since 0.1.0
     */
    protected function createTestInstance(
        Configuration $configuration = null,
        FileLocator $fileLocator = null,
        JavaExecutableLocator $javaLocator = null,
        JarResolver $jarResolver = null,
        Filesystem $filesystemHelper = null
    ) {
        $instance = parent::createTestInstance(
            $configuration ?: new Configuration,
            $fileLocator ?: $this->createFileLocatorMock(),
            $javaLocator ?: $this->createJavaLocatorMock(),
            $jarResolver ?: $this->createJarResolverMock(),
            $filesystemHelper ?: $this->createFilesystemHelperMock(),
            new DummyController
        );
        return $instance;
    }

    /**
     * Creates file locator mock
     *
     * @param Closure $executableLocator Executable locator mock.
     *
     * @return Mock|FileLocator
     * @since 0.1.0
     */
    private function createFileLocatorMock(Closure $executableLocator = null)
    {
        $mock = $this
            ->getMockFactory(Registry::FILE_LOCATOR_CLASS)
            ->getConstructorlessMock();
        $invocationMocker = $mock
            ->expects($this->any())
            ->method('locateExecutable');
        if ($executableLocator) {
            $invocationMocker->willReturnCallback($executableLocator);
        } else {
            $invocationMocker->willReturn(false);
        }
        return $mock;
    }

    /**
     * Creates java executable locator mock.
     *
     * @param string $javaExecutableLocation Location to return on
     *                                       `getJavaExecutable()` call.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Mock|JavaExecutableLocator
     * @since 0.1.0
     */
    private function createJavaLocatorMock($javaExecutableLocation = null)
    {
        $mock = $this
            ->getMockFactory(Registry::JAVA_EXECUTABLE_LOCATOR_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getJavaExecutable')
            ->willReturn($javaExecutableLocation);
        return $mock;
    }

    /**
     * Creates jar resolver mock.
     *
     * @param string $jarLocation Location to return on `resolveJar()` call.
     *
     * @return Mock
     * @since 0.1.0
     */
    private function createJarResolverMock($jarLocation = null)
    {
        $mock = $this
            ->getMockFactory(Registry::JAR_RESOLVER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('resolveJar')
            ->willReturn($jarLocation);
        return $mock;
    }

    /**
     * Creates filesystem helper mock.
     *
     * @param Closure $existenceChecker  Existence checker function.
     * @param Closure $executableChecker Executable checker function.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Mock|Filesystem
     * @since 0.1.0
     */
    private function createFilesystemHelperMock(
        Closure $existenceChecker = null,
        Closure $executableChecker = null
    ) {
        $mock = $this
            ->getMockFactory(Registry::FILESYSTEM_HELPER_CLASS)
            ->getConstructorlessMock();
        $existenceCheckMocker = $mock
            ->expects($this->any())
            ->method('exists');
        if ($existenceChecker) {
            $existenceCheckMocker->willReturnCallback($existenceChecker);
        } else {
            $existenceCheckMocker->willReturn(false);
        }
        $executableCheckMocker = $mock
            ->expects($this->any())
            ->method('isExecutable');
        if ($executableChecker) {
            $executableCheckMocker->willReturnCallback($executableChecker);
        } else {
            $executableCheckMocker->willReturn(false);
        }
        return $mock;
    }

    // tests

    /**
     * Tests scenario where allure executable shouldn't be found at all.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingScenario()
    {
        $this->assertNull($this->createTestInstance()->getAllureExecutable());
    }

    /**
     * Tests scenario in which generic executable is found.
     *
     * @return void
     * @since 0.1.0
     */
    public function testGenericExecutableResolveScenario()
    {
        $location = '~/.bin/allure';
        $executableLocator = function () use ($location) {
            return array($location,);
        };
        $executableChecker = function () {
            return true;
        };
        $filesystemMock
            = $this->createFilesystemHelperMock(null, $executableChecker);
        $fileLocatorMock = $this->createFileLocatorMock($executableLocator);
        $instance = $this->createTestInstance(
            null,
            $fileLocatorMock,
            null,
            null,
            $filesystemMock
        );
        $this->assertSame($location, $instance->getAllureExecutable());
    }

    /**
     * Tests scenario where generic executable fails to be resolved.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingGenericExecutableResolveScenario()
    {
        $executableLocator = function () {
            return null;
        };
        $fileLocatorMock = $this->createFileLocatorMock($executableLocator);
        $instance = $this->createTestInstance(null, $fileLocatorMock);
        $this->assertNull($instance->getAllureExecutable());
    }

    /**
     * Tests scenario when found executables are not really executables.
     *
     * Yes, just for 100% coverage.
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidExecutableSearchScenario()
    {
        $location = '~/.bin/allure';
        $executableLocator = function () use ($location) {
            return array($location,);
        };
        $existenceChecker = function () {
            return true;
        };
        $executableChecker = function () {
            return false;
        };
        $filesystemMock = $this->createFilesystemHelperMock(
            $existenceChecker,
            $executableChecker
        );
        $fileLocatorMock = $this->createFileLocatorMock($executableLocator);
        $instance = $this->createTestInstance(
            null,
            $fileLocatorMock,
            null,
            null,
            $filesystemMock
        );
        $this->assertNull($instance->getAllureExecutable());
    }

    /**
     * Tests scenario in which jar location can't be resolved.
     *
     * @return void
     * @since 0.1.0
     */
    public function testMissingJarLocationScenario()
    {
        $jarResolverMock = $this->createJarResolverMock(null);
        $javaLocator = $this->createJavaLocatorMock('/usr/bin/java');
        $instance = $this->createTestInstance(
            null,
            null,
            $javaLocator,
            $jarResolverMock
        );
        $this->assertNull($instance->getAllureExecutable());
    }

    /**
     * Tests scenario in which jar location is resolved correctly.
     *
     * @return void
     * @since 0.1.0
     */
    public function testCorrectJarLocationScenario()
    {
        $javaLocation = '/usr/bin/java';
        $jarLocation = '/tmp/allure-cli.jar';
        $jarResolverMock = $this->createJarResolverMock($jarLocation);
        $javaLocator = $this->createJavaLocatorMock($javaLocation);
        $instance = $this->createTestInstance(
            null,
            null,
            $javaLocator,
            $jarResolverMock
        );
        $executable = $instance->getAllureExecutable();
        $this->assertStringMatchesFormat("%S$javaLocation%S", $executable);
        $this->assertStringMatchesFormat("%S$jarLocation%S", $executable);
    }

    /**
     * Tests scenario with configuration-specified executable.
     *
     * @return void
     * @since 0.1.0
     */
    public function testConfigurationSpecifiedExecutableScenario()
    {
        $location = '~/.bin/allure';
        $configuration = new Configuration;
        $configuration->setExecutable($location);
        $existenceChecker = $executableChecker = function () {
            return true;
        };
        $filesystemMock = $this->createFilesystemHelperMock(
            $existenceChecker,
            $executableChecker
        );
        $instance = $this->createTestInstance(
            $configuration,
            null,
            null,
            null,
            $filesystemMock
        );
        $this->assertSame($location, $instance->getAllureExecutable());
    }

    /**
     * Tests scenario when configuration-specified executable couldn't be found.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingConfigurationSpecifiedExecutableScenario()
    {
        $location = '~/.bin/allure';
        $configuration = new Configuration;
        $configuration->setExecutable($location);
        $existenceChecker = function () {
            return true;
        };
        $filesystemMock = $this->createFilesystemHelperMock($existenceChecker);
        $instance = $this->createTestInstance(
            $configuration,
            null,
            null,
            null,
            $filesystemMock
        );
        $this->assertNull($instance->getAllureExecutable());
    }
}
