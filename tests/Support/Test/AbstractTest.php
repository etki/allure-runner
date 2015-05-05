<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Test;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\DI\ContainerBuilder;
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
     * @param IOControllerInterface $ioController  I/O controller.
     *
     * @return Container Container instance.
     * @since 0.1.0
     */
    public function createContainer(
        Configuration $configuration = null,
        $ioController = null
    ) {
        $builder = new ContainerBuilder;
        $pathResolver
            = new PathResolver(CodeceptionConfiguration::projectDir());
        $configuration = $configuration ?: new Configuration;
        if (!$ioController) {
            $ioController = $this
                ->getMockFactory(self::IO_CONTROLLER_INTERFACE)
                ->getDummyMock();
        }
        return $builder->build($pathResolver, $configuration, $ioController);
    }
}
