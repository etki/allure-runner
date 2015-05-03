<?php

namespace Etki\Testing\AllureFramework\Runner;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\ConfigurationValidator;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\DI\ContainerBuilder;
use Etki\Testing\AllureFramework\Runner\Utility\Helper\ConfigurationDumper;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
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
        if (!$container) {
            $container = $this->createContainer($configuration, $ioController);
        }
        $this->container = $container;
        $this->configuration = $configuration;
        $this->ioController = $ioController ?: $container->get('io_controller');
        $this->configureIoController($this->ioController);
        $message = 'Allure Runner has successfully initialized';
        $this->ioController->writeLine($message, Verbosity::LEVEL_DEBUG);
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
        $pathResolver = new PathResolver($projectRoot);
        $container = $builder->build(
            $pathResolver,
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
        $this->ioController->writeLine(
            'Starting Allure CLI processing',
            Verbosity::LEVEL_NOTICE
        );
        $dumper = new ConfigurationDumper;
        $dumper->dump($this->configuration, $this->ioController);
        /** @type Scenario $scenario */
        $scenario = $this->container->get('scenario');
        if (!$this->validateConfiguration($this->configuration)) {
            // todo
        }
        return $scenario->run();
    }

    /**
     * Configures I/O controller - sets verbosity and output prefix.
     *
     * @param IOControllerInterface $ioController I/O controller.
     *
     * @return void
     * @since 0.1.0
     */
    private function configureIoController($ioController)
    {
        if ($this->configuration->getVerbosity()) {
            $verbosity = $this->configuration->getVerbosity();
            $ioController->setVerbosity($verbosity);
        }
        if ($this->configuration->getOutputPrefixFormat()
            && $ioController instanceof PrefixAwareIOControllerInterface
        ) {
            $prefixFormat = $this->configuration->getOutputPrefixFormat();
            $ioController->setPrefixFormat($prefixFormat);
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
        /** @type ConfigurationValidator $validator */
        $validator = $this->container->get('configuration_validator');
        return $validator->validate($configuration);
    }
}
