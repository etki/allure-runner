<?php

namespace Etki\Testing\AllureFramework\Runner\DI;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as Container;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Validator\Validation;

/**
 * Builds container.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\DI
 * @author  Etki <etki@etki.name>
 */
class ContainerBuilder
{
    /**
     * Builds container.
     *
     * @param string[]  $configurationPaths List of container configuration
     *                                      paths.
     * @param object[]  $extraServices      List of extra services not specified
     *                                      in configuration.
     * @param mixed[]   $extraParameters    Extra container parameters not
     *                                      specified in configuration files
     *                                      (e.g. runtime configuration).
     * @param Container $container          Container instance, if already
     *                                      created.
     *
     * @return Container
     * @since 0.1.0
     */
    public function build(
        array $configurationPaths,
        array $extraServices = array(),
        array $extraParameters = array(),
        Container $container = null
    ) {
        if (!$container) {
            $container = $this->createContainer($configurationPaths);
        }
        foreach ($extraServices as $key => $service) {
            $container->set($key, $service);
        }
        foreach ($extraParameters as $key => $parameter) {
            $container->setParameter($key, $parameter);
        }
        $this->injectMissingDependencies($container);
        return $container;
    }

    /**
     * Creates container instance.
     *
     * @param string[] $configurationPaths List of paths to configuration files.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Container
     * @since 0.1.0
     */
    private function createContainer(array $configurationPaths)
    {
        $container = new Container;
        $locator = new FileLocator;
        $loader = new YamlFileLoader($container, $locator);
        foreach ($configurationPaths as $configurationFilePath) {
            $loader->load($configurationFilePath);
        }
        return $container;
    }

    /**
     * Injects dependencies that are not set by default in container for any
     * reason.
     *
     * @param Container $container Container instance.
     *
     * @return void
     * @since 0.1.0
     */
    private function injectMissingDependencies(Container $container)
    {
        $container->set('service_container', $container);
        $annotationReader = new AnnotationReader;
        // todo extend default reader and fix it inside
        $annotationReader->addGlobalIgnoredName('type');
        AnnotationRegistry::registerLoader('class_exists');
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping($annotationReader)
            ->getValidator();
        $container->set('validator', $validator);
    }
}
