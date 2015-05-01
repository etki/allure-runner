<?php

namespace Etki\Testing\AllureFramework\Runner\DI;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorFactory;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as Container;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
     * @param string $configurationPath Path to configuration file.
     *
     * @return Container
     * @since 0.1.0
     */
    public function build(
        $configurationPath,
        Configuration $configuration,
        IOControllerInterface $ioController = null
    ) {
        $container = new Container;
        $locator = new FileLocator;
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($configurationPath);
        $container->set('service_container', $container);
        $container->setParameter('configuration', $configuration);
        if ($ioController) {
            $container->set('io_controller', $ioController);
        }
        /** @type FileLocatorFactory $fileLocatorFactory */
        $fileLocatorFactory = $container->get('file_locator_factory');
        $container->set('file_locator', $fileLocatorFactory->getFileLocator());
        return $container;
    }
}
