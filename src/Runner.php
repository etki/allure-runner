<?php

namespace Etki\Testing\AllureFramework\Runner;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Helper\ConfigurationDumper;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;

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
     * Software name.
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
     * I/O controller.
     *
     * @type IOControllerInterface|PrefixAwareIOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    public function __construct(
        Configuration $configuration,
        IOControllerInterface $ioController = null
    ) {
        $this->configuration = $configuration;
        if (!$ioController) {
            $ioController = new DummyController;
        }
        $this->ioController = $ioController;
        //$this->validateConfiguration();
        $this->configureIoController();
        $dumper = new ConfigurationDumper;
        $dumper->dump($configuration, $this->ioController);
    }
    
    /**
     * Runs command.
     *
     * @param Configuration $configuration
     *
     * @return int Standard exit code.
     * @since 0.1.0
     */
    public function run()
    {
        $executable = $this->getExecutable();
        if (!$executable) {
            $this->ioController->writeLine(
                'Couldn\'t find allure executable, halting',
                Verbosity::LEVEL_ERROR
            );
            return 1;
        }
        $commandBuilder = new CommandBuilder;
    }

    private function configureIoController() {
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
    
    private function getExecutable()
    {
        $executable = $this->configuration->getExecutable();
        if (!$executable) {

        }
    }
}
