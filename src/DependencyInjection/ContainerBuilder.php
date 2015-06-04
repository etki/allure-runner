<?php

namespace Etki\Testing\AllureFramework\Runner\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as Container;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Builds container.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\DependecyInjection
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
        $container = $container ?: new Container;
        $container = $this->populateContainer($container, $configurationPaths);
        foreach ($extraServices as $key => $service) {
            $container->set($key, $service);
        }
        foreach ($extraParameters as $key => $parameter) {
            $container->setParameter($key, $parameter);
        }
        return $container;
    }

    /**
     * Creates container instance.
     *
     * @param Container $container          Container instance.
     * @param string[]  $configurationPaths List of paths to configuration
     *                                      files.
     *
     * @return Container
     * @since 0.1.0
     */
    private function populateContainer(
        Container $container,
        array $configurationPaths
    ) {
        $loader = new YamlFileLoader($container, new FileLocator);
        foreach ($configurationPaths as $path) {
            $loader->load($path);
        }
        return $container;
    }
}
