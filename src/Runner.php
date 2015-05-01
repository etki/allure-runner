<?php

namespace Etki\Testing\AllureFramework\Runner;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\DI\ContainerBuilder;
use Etki\Testing\AllureFramework\Runner\Helper\ConfigurationDumper;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario;
use Symfony\Component\DependencyInjection\ContainerBuilder
    as Container;

/**
 * Runs command.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner
 * @author  Etki <etki@etki.name>
 */
class Runner
{
    /**
     * Default software name.
     *
     * @since 0.1.0
     */
    const SOFTWARE_NAME = 'Allure Runner';
    /**
     * Runner configuration.
     *
     * @type Configuration
     * @since 0.1.0
     */
    private $configuration;
    /**
     * DI container.
     *
     * @type Container
     * @since 0.1.0
     */
    private $container;
    /**
     * I/O controller.
     *
     * @type IOControllerInterface|PrefixAwareIOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer
     *
     * @param Configuration         $configuration Configuration instance.
     * @param IOControllerInterface $ioController  I/O controller.
     * @param Container             $container     DI container.
     *
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        IOControllerInterface $ioController = null,
        Container $container = null
    ) {
        $this->validateConfiguration($configuration);
        if (!$container) {
            $container = $this->createContainer($configuration, $ioController);
        }
        $this->container = $container;
        $this->configuration = $configuration;
        $this->ioController = $ioController ?: $container->get('io_controller');
        $this->configureIoController();
    }

    /**
     * Creates container.
     *
     * @param Configuration         $configuration Configuration instance.
     * @param IOControllerInterface $ioController  I/O controller.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Container
     * @since 0.1.0
     */
    private function createContainer(
        Configuration $configuration,
        IOControllerInterface $ioController = null
    ) {
        $builder = new ContainerBuilder;
        $projectRoot = dirname(__DIR__);
        $configurationFilePath = $projectRoot . DIRECTORY_SEPARATOR .
            Configuration::CONTAINER_CONFIGURATION_FILE_PATH;
        $container = $builder->build(
            $configurationFilePath,
            $configuration,
            $ioController
        );
        return $container;
    }
    
    /**
     * Runs command.
     *
     * @return int Standard exit code.
     * @since 0.1.0
     */
    public function run()
    {
        $dumper = new ConfigurationDumper;
        $dumper->dump($this->configuration, $this->ioController);
        /** @type Scenario $scenario */
        $scenario = $this->container->get('scenario');
        $scenario->run();
    }

    /**
     * Configures I/O controller - sets verbosity and output prefix.
     *
     * @return void
     * @since 0.1.0
     */
    private function configureIoController()
    {
        if ($this->configuration->getVerbosity()) {
            $verbosity = $this->configuration->getVerbosity();
            $this->ioController->setVerbosity($verbosity);
        }
        if ($this->configuration->getOutputPrefixFormat()
            && $this->ioController instanceof PrefixAwareIOControllerInterface
        ) {
            $prefixFormat = $this->configuration->getOutputPrefixFormat();
            $this->ioController->setPrefixFormat($prefixFormat);
        }
        
    }

    /**
     * Validates configuration.
     *
     * @param Configuration $configuration Configuration to validate.
     *
     * @return bool
     * @since 0.1.0
     */
    private function validateConfiguration(Configuration $configuration)
    {
        // $validator =
        // if (!$validate
        // throw new BadConfigurationException. 
            
        return true;
    }
}
