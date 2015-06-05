<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Configuration;

use Etki\Testing\AllureFramework\Runner\Configuration\Builder;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Schema;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Symfony\Component\Yaml\Yaml;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests configuration builder.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Configuration
 * @author  Etki <etki@etki.name>
 */
class BuilderTest extends AbstractClassAwareTest
{
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
        return Registry::CONFIGURATION_BUILDER_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param Filesystem            $filesystem   Filesystem helper.
     * @param PathResolver          $pathResolver Path resolver.
     * @param IOControllerInterface $ioController I/O controller.
     *
     * @return Builder
     * @since 0.1.0
     */
    public function createTestInstance(
        Filesystem $filesystem = null,
        PathResolver $pathResolver = null,
        IOControllerInterface $ioController = null
    ) {
        $filesystem = $filesystem ?: $this->createFilesystemHelperMock('');
        $pathResolver = $pathResolver ?: $this->createPathResolverMock('');
        $ioController = $ioController ?: $this->createIoControllerMock();
        $instance = parent::createTestInstance(
            $filesystem,
            $pathResolver,
            $ioController
        );
        return $instance;
    }

    /**
     * Creates filesystem helper mock.
     *
     * @param string $returnedContent    Content to return on `readFile` call.
     * @param string $temporaryDirectory Value to return on
     *                                   `getTemporaryDirectory()` call.
     *
     * @return Filesystem|Mock
     * @since 0.1.0
     */
    private function createFilesystemHelperMock(
        $returnedContent,
        $temporaryDirectory = null
    ) {
        $mock = $this
            ->getMockFactory(Registry::FILESYSTEM_HELPER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('readFile')
            ->willReturn($returnedContent);
        $mock
            ->expects($this->any())
            ->method('getTemporaryDirectory')
            ->willReturn($temporaryDirectory);
        return $mock;
    }

    /**
     * Creates path resolver.
     *
     * @param string $returnedPath Path to return on configuration file search.
     *
     * @return PathResolver|Mock
     * @since 0.1.0
     */
    private function createPathResolverMock($returnedPath)
    {
        $mock = $this
            ->getMockFactory(Registry::PATH_RESOLVER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getConfigurationFile')
            ->willReturn($returnedPath);
        return $mock;
    }

    /**
     * Creates I/O controller mock.
     *
     * @return IOControllerInterface|Mock
     * @since 0.1.0
     */
    private function createIoControllerMock()
    {
        $mock = $this
            ->getMockFactory(Registry::IO_CONTROLLER_INTERFACE)
            ->getDummyMock();
        return $mock;
    }

    /**
     * Calculates setter name for a parameter.
     *
     * @param string $parameter Parameter name.
     *
     * @return string Setter name.
     * @since 0.1.0
     */
    private function getSetterName($parameter)
    {
        $uncommonSetters = array(
            Schema::PARAMETER_ENABLED => 'setIsEnabled',
            Schema::PARAMETER_SOURCES => 'addSources',
        );
        if (in_array($parameter, array_keys($uncommonSetters), true)) {
            return $uncommonSetters[$parameter];
        }
        return 'set' . ucfirst($parameter);
    }

    /**
     * Creates prepared configuration mock
     *
     * @param string[] $expectedParameters List of parameters that are going to
     *                                     be set.
     *
     * @return Configuration|Mock
     * @since 0.1.0
     */
    private function createPreparedConfigurationMock(
        array $expectedParameters = array()
    ) {
        $mock = $this
            ->getMockFactory(Registry::CONFIGURATION_CLASS)
            ->getMock();
        foreach ($expectedParameters as $parameter) {
            $mock
                ->expects($this->atLeastOnce())
                ->method($this->getSetterName($parameter))
                ->willReturn($mock);
        }
        return $mock;
    }

    /**
     * Provides sets of configuration.
     *
     * @return array
     * @since 0.1.0
     */
    public function configurationProvider()
    {
        return array(
            array(
                array(
                    Schema::PARAMETER_CLEAN_GENERATED_FILES => true,
                    Schema::PARAMETER_DOWNLOAD_MISSING_JAR => true,
                    Schema::PARAMETER_DRY_RUN => true,
                    Schema::PARAMETER_ENABLED => true,
                    Schema::PARAMETER_EXECUTABLE => 'allure',
                    Schema::PARAMETER_JAR => 'allure.jar',
                    Schema::PARAMETER_OUTPUT_PREFIX_FORMAT => '{date} {time}',
                    Schema::PARAMETER_PREFERRED_ALLURE_VERSION => '99.9',
                    Schema::PARAMETER_REPORT_PATH => '/tmp/report',
                    Schema::PARAMETER_REPORT_VERSION => '1.9.9',
                    Schema::PARAMETER_SOURCES => array('/tmp/sources',),
                    Schema::PARAMETER_TEMPORARY_DIRECTORY => '/tmp',
                    Schema::PARAMETER_VERBOSITY => 'auto',
                    Schema::PARAMETER_USE_VFS => true,
                    Schema::PARAMETER_THROW_ON_INVALID_CONFIGURATION => true,
                    Schema::PARAMETER_THROW_ON_MISSING_EXECUTABLE => true,
                    Schema::PARAMETER_THROW_ON_NON_ZERO_EXIT_CODE => true,
                ),
            )
        );
    }

    // tests

    /**
     * Tests configuration populating.
     *
     * @param array $values List of configuration values.
     *
     * @dataProvider configurationProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testPopulating(array $values)
    {
        $configuration
            = $this->createPreparedConfigurationMock(array_keys($values));
        $builder = $this->createTestInstance();
        $builder->populate($configuration, $values);
    }

    /**
     * Tests configuration building.
     *
     * todo this actually belongs to higher-level testing
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testBuild()
    {
        $userDefinedExecutable = '/usr/bin/allure';
        $userDefinedJar = '/tmp/allure-cli.jar';
        $defaultVerbosity = Verbosity::LEVEL_DEBUG;
        
        $defaults = array(
            Schema::PARAMETER_VERBOSITY => $defaultVerbosity,
            Schema::PARAMETER_EXECUTABLE => 'allure',
        );
        $values = array(
            Schema::PARAMETER_EXECUTABLE => $userDefinedExecutable,
            Schema::PARAMETER_JAR => $userDefinedJar,
        );
        
        $yaml = Yaml::dump($defaults);
        $filesystemHelper = $this->createFilesystemHelperMock($yaml);
        $instance = $this->createTestInstance($filesystemHelper);
        $configuration = $instance->build($values);
        
        $this->assertSame(
            $userDefinedExecutable,
            $configuration->getExecutable()
        );
        $this->assertSame($userDefinedJar, $configuration->getJar());
        $this->assertSame($defaultVerbosity, $configuration->getVerbosity());
    }

    /**
     * Tests how parameters are resolved inside builder.
     *
     * @return void
     * @since 0.1.0
     */
    public function testParameterResolve()
    {
        $defaultVerbosity = Verbosity::LEVEL_DEBUG;
        $defaultExecutable = 'allure';
        $reportPath = '%STRANGE_REPORT_PATH%';
        $temporaryDirectory = ':temporary directory:';
        
        $defaults = array(
            Schema::PARAMETER_VERBOSITY => $defaultVerbosity,
            Schema::PARAMETER_EXECUTABLE => $defaultExecutable,
        );
        $values = array(
            Schema::PARAMETER_PREFERRED_ALLURE_VERSION
                => Configuration::VALUE_AUTO,
            Schema::PARAMETER_VERBOSITY => Configuration::VALUE_AUTO,
            Schema::PARAMETER_EXECUTABLE => Configuration::VALUE_AUTO,
            Schema::PARAMETER_REPORT_PATH => $reportPath,
            // special stupid section for 100% coverage
            // normal people don't do it, but i do
            Schema::PARAMETER_REPORT_VERSION => Configuration::VALUE_AUTO,
            Schema::PARAMETER_TEMPORARY_DIRECTORY => Configuration::VALUE_AUTO,
            Schema::PARAMETER_OUTPUT_PREFIX_FORMAT => Configuration::VALUE_AUTO,
        );
        $yaml = Yaml::dump($defaults);
        $filesystem
            = $this->createFilesystemHelperMock($yaml, $temporaryDirectory);
        $instance = $this->createTestInstance($filesystem);
        $configuration = $instance->build($values);
        
        $this->assertSame(
            Configuration::DEFAULT_ALLURE_VERSION,
            $configuration->getPreferredAllureVersion()
        );
        $this->assertSame(
            Configuration::DEFAULT_VERBOSITY_LEVEL,
            $configuration->getVerbosity()
        );
        $this->assertSame(
            $defaultExecutable,
            $configuration->getExecutable()
        );
        $this->assertSame(
            $reportPath,
            $configuration->getReportPath()
        );
    }

    /**
     * Verifies that exception is thrown on unknown parameters.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Configuration\UnknownParameterException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidParameterReaction()
    {
        $configuration = $this->createPreparedConfigurationMock();
        $instance = $this->createTestInstance();
        $instance->populate($configuration, array(':nonexistent' => 'value',));
    }
}
