<?php

namespace Etki\Testing\AllureFramework\Runner\Run;

use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\AllureExecutableResolver;

use Exception;

/**
 * This is an actual scenario that takes place during single run.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run
 * @author  Etki <etki@etki.name>
 */
class Scenario
{
    /**
     * Configuration instance.
     *
     * @type Configuration
     * @since 0.1.0
     */
    private $configuration;
    /**
     * I/O controller instance.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * Instance of generic executable resolver.
     *
     * @type AllureExecutableResolver
     * @since 0.1.0
     */
    private $allureResolver;

    /**
     * The real runner.
     *
     * @type Runner
     * @since 0.1.0
     */
    private $runner;

    /**
     * Initializer.
     *
     * @param Configuration            $configuration  Allure configuration.
     * @param AllureExecutableResolver $allureResolver Allure executable
     *                                                 resolver.
     * @param Runner                   $runner         Real runner.
     * @param IOControllerInterface    $ioController   I/O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        AllureExecutableResolver $allureResolver,
        Runner $runner,
        IOControllerInterface $ioController
    ) {
        $this->configuration = $configuration;
        $this->allureResolver = $allureResolver;
        $this->runner = $runner;
        $this->ioController = $ioController;
    }

    /**
     * Runs scenario.
     *
     * @return int Exit code.
     * @since 0.1.0
     */
    public function run()
    {
        $executable = $this->getExecutable();
        if (!$executable) {
            $this->ioController->writeLine(
                'Couldn\'t find Allure executable',
                Verbosity::LEVEL_ERROR
            );
            return 1;
        }
        $runOptions = $this->createRunOptions();
        try {
            return $this->runner->run($executable, $runOptions, $this->ioController);
        } catch (Exception $e) {
            $this->ioController->writeLine(
                $e->getMessage(),
                Verbosity::LEVEL_ERROR
            );
            return $e->getCode();
        }
    }

    /**
     * Locates Allure executable.
     *
     * @return string
     * @since 0.1.0
     */
    private function getExecutable()
    {
        $command = $this->allureResolver->getAllureExecutable();
        if (!$command) {
            throw new AllureExecutableNotFoundException;
        }
        return $command;
    }

    /**
     * Creates run options container for runner.
     *
     * @return RunOptions
     * @since 0.1.0
     */
    private function createRunOptions()
    {
        $options = new RunOptions;
        $options->setReportPath($this->configuration->getReportPath());
        $options->setReportVersion($this->configuration->getReportVersion());
        $options->setSources($this->configuration->getSources());
        return $options;
    }
}
