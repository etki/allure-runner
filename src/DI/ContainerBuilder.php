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
     * @param PathResolver          $pathResolver  PathResolver instance.
     * @param Configuration         $configuration Configuration instance.
     * @param IOControllerInterface $ioController  I/O controller.
     *
     * @return Container
     * @since 0.1.0
     */
    public function build(
        PathResolver $pathResolver,
        Configuration $configuration,
        IOControllerInterface $ioController = null
    ) {
        $container = $this->createContainer($pathResolver);
        $container->set('path_resolver', $pathResolver);
        $container->setParameter('configuration', $configuration);
        if ($ioController) {
            $container->set('io_controller', $ioController);
        }
        $this->injectMissingDependencies($container);
        $container->compile();
        return $container;
    }

    /**
     * Creates container instance.
     *
     * @param PathResolver $pathResolver
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Container
     * @since 0.1.0
     */
    private function createContainer(PathResolver $pathResolver)
    {
        $container = new Container;
        $locator = new FileLocator;
        $loader = new YamlFileLoader($container, $locator);
        $configurationFileName
            = Configuration::CONTAINER_CONFIGURATION_FILE_NAME;
        $configurationFilePath
            = $pathResolver->getConfigurationFile($configurationFileName);
        $loader->load($configurationFilePath);
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
