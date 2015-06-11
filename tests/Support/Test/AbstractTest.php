<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Test;

use Codeception\Util\Debug;
use DateTime;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\DependencyInjection\ContainerBuilder;
use Etki\Testing\AllureFramework\Runner\IO\Controller\ConsoleIOController;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api\BaseApiResponseLoader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Debug\DebugWriter;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\BasicMockFactory;
use Codeception\Configuration as CodeceptionConfiguration;
use Codeception\TestCase\Test;
use Symfony\Component\DependencyInjection\ContainerBuilder as Container;

/**
 * Base test.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Test
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractTest extends Test
{
    /**
     * I/O controller FQIN.
     *
     * @since 0.1.0
     */
    const IO_CONTROLLER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface';
    /**
     * Namespace factories reside in.
     *
     * @since 0.1.0
     */
    const FACTORY_BASE_NAMESPACE
        = 'Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory';
    /**
     * Namespace regular components live in.
     *
     * @since 0.1.0
     */
    const NATIVE_COMPONENT_BASE_NAMESPACE
        = 'Etki\Testing\AllureFramework\Runner';
    /**
     * Namespace API response loaders reside in.
     *
     * @since 0.1.0
     */
    const API_RESPONSE_LOADER_NAMESPACE
        = 'Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api';
    /**
     * Returns mock factory.
     *
     * @param string $class Name of the class to return factory for.
     *
     * @return BasicMockFactory|AbstractMockFactory
     * @since 0.1.0
     */
    public function getMockFactory($class)
    {
        $baseNativeNamespace = self::NATIVE_COMPONENT_BASE_NAMESPACE;
        if (strpos($class, $baseNativeNamespace) === 0) {
            $factoryClassNameBase = str_replace(
                $baseNativeNamespace,
                self::FACTORY_BASE_NAMESPACE,
                $class
            );
            $factoryClassName = $factoryClassNameBase . 'MockFactory';
        } else {
            $factoryClassName = sprintf(
                '%s\Vendor\%sMockFactory',
                self::FACTORY_BASE_NAMESPACE,
                $class
            );
        }
        // todo Yii-compatibility
        if (class_exists($factoryClassName)) {
            /** @type AbstractMockFactory $factory */
            $factory = new $factoryClassName($this);
        } else {
            $factory = new BasicMockFactory($class);
        }
        $factory->setTest($this);
        return $factory;
    }

    /**
     * This function should be erm avoided at all costs, whenever possible. It
     * relies on several classes that are not tested at the moment of use.
     *
     * Returns container instance, just as in real run.
     *
     * @param Configuration         $configuration Runner configuration.
     * @param object[]              $services      Additional services.
     * @param IOControllerInterface $ioController  I/O controller.
     *
     * @return Container Container instance.
     * @since 0.1.0
     */
    public function createContainer(
        Configuration $configuration = null,
        array $services = array(),
        $ioController = null
    ) {
        $builder = new ContainerBuilder;
        $pathResolver
            = new PathResolver(CodeceptionConfiguration::projectDir());
        $configuration = $configuration ?: new Configuration;
        if (!$ioController) {
            $verbosityLevel = Verbosity::LEVEL_DEBUG;
            $writer = new DebugWriter;
            $ioController = new ConsoleIOController($writer, $verbosityLevel);
        }
        $services['path_resolver'] = $pathResolver;
        $services['io_controller'] = $ioController;
        $fileName = Configuration::CONTAINER_CONFIGURATION_FILE_NAME;
        $configurationPath = $pathResolver->getConfigurationFile($fileName);
        $container = $builder->build(
            array($configurationPath,),
            $services,
            array('configuration' => $configuration,)
        );
        return $container;
    }

    /**
     * Strips I/O services from container, so nothing can perform I/O during
     * tests (if necessary).
     *
     * @param Container $container  Container instance.
     * @param string[]  $exclusions List of services that should not be
     *                             overriden.
     *
     * @return void
     * @since 0.1.0
     */
    public function wipeContainerIoServices(
        Container $container,
        array $exclusions = array()
    ) {
        $services = array(
            'io_controller' => new DummyController,
            'php_filesystem_api' => $this->getMockFactory(Registry::PHP_FILESYSTEM_API_CLASS)->getDummyMock(),
            'github_api_client' => $this->getMockFactory(Registry::GITHUB_API_CLIENT_CLASS)->getDummyMock(),
            'guzzle' => $this->getMockFactory(Registry::GUZZLE_CLIENT_CLASS)->getDummyMock(),
            'symfony_filesystem' => $this->getMockFactory(Registry::SYMFONY_FILESYSTEM_CLASS)->getDummyMock(),
            'zip_extractor' => $this->getMockFactory(Registry::EXTRACTOR_CLASS)->getDummyMock(),
        );
        foreach ($services as $id => $service) {
            if (!in_array($id, $exclusions, true)) {
                $container->set($id, $service);
            }
        }
    }

    /**
     * Retrieves particular API response loader.
     *
     * @param string $api Api name.
     *
     * @return BaseApiResponseLoader
     * @since 0.1.0
     */
    public function getResponseLoader($api)
    {
        $api = ucfirst($api);
        $baseDirectory = CodeceptionConfiguration::dataDir() . '/Samples/Api/' .
            $api;
        $expectedClass = self::API_RESPONSE_LOADER_NAMESPACE . '\\' . $api;
        if (class_exists($expectedClass)) {
            return new $expectedClass($baseDirectory);
        }
        return new BaseApiResponseLoader($baseDirectory);
    }

    /**
     * Outputs a debug message.
     *
     * @param string $message Message to output.
     *
     * @return void
     * @since 0.1.0
     */
    protected function debug($message)
    {
        $timestamp = DateTime::createFromFormat('U.u', microtime(true));
        
        Debug::debug($timestamp->format('Y-m-d\TH:i:s.u') . ' ' . $message);
    }
}
