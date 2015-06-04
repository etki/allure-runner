<?php

namespace Etki\Testing\AllureFramework\Runner;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\ConfigurationValidator;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\DependencyInjection\ContainerBuilder;
use Etki\Testing\AllureFramework\Runner\Exception\AllureCli\NonZeroExitCodeException;
use Etki\Testing\AllureFramework\Runner\Exception\Configuration\InvalidConfigurationException;
use Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerConfigurator;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Etki\Testing\AllureFramework\Runner\Utility\Helper\ConfigurationDumper;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
use Exception;
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
        $configurationFileName
            = Configuration::CONTAINER_CONFIGURATION_FILE_NAME;
        $configurationPath
            = $pathResolver->getConfigurationFile($configurationFileName);
        $services = array(
            'io_controller' => $ioController,
            'path_resolver' => $pathResolver,
        );
        $container = $builder->build(
            array($configurationPath,),
            $services,
            array('configuration' => $configuration,)
        );
        $container->compile();
        return $container;
    }
    
    /**
     * Runs command.
     *
     * @return Report Run report.
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
            if ($this->configuration->shouldThrowOnInvalidConfiguration()) {
                $message = InvalidConfigurationException::getDefaultMessage();
                throw new InvalidConfigurationException($message);
            }
            return new Report(Report::STATUS_CANCELLED);
        }
        $report = $scenario->run();
        $this->handleRunResult($report);
        return $report;
    }

    /**
     * Works post-run work.
     *
     * @param Report $report Report to examine.
     *
     * @throws Exception Exception      Throws whatever exception has been
     *                                  raised during the run if it hasn't been
     *                                  prohibited by configuration.
     * @throws NonZeroExitCodeException Thrown if run has been unsuccessful and
     *                                  configuration tells to throw this
     *                                  exception.
     *
     * @return void
     * @since 0.1.0
     */
    private function handleRunResult(Report $report)
    {
        if ($exception = $report->getException()) {
            if (!($exception instanceof AllureExecutableNotFoundException)
                || $this->configuration->shouldThrowOnMissingExecutable()
            ) {
                throw $exception;
            }
        }
        if ($report->getExitCode()
            && $this->configuration->shouldThrowOnNonZeroExitCode()
        ) {
            $message = sprintf(
                'Allure run has finished with exit code other than zero (%d)',
                $report->getExitCode()
            );
            throw new NonZeroExitCodeException($message);
        }
    }

    /**
     * Configures I/O controller - sets verbosity and output prefix.
     *
     * @param IOControllerInterface $ioController I/O controller.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    private function configureIoController($ioController)
    {
        /** @type IOControllerConfigurator $configurator */
        $configurator = $this->container->get('io_controller_configurator');
        $configurator->configure($ioController, $this->configuration);
    }

    /**
     * Validates configuration.
     *
     * @param Configuration $configuration Configuration to validate.
     *
     * @codeCoverageIgnore
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
