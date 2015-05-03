<?php

namespace Etki\Testing\AllureFramework\Runner\Run;

use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\AllureExecutableResolver;
use Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException;

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
            $exitCode = $this->runner->run($executable, $runOptions);
        } catch (Exception $e) {
            $message = sprintf(
                'Run terminated with exception `%s`:',
                get_class($e)
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
            $message = $e->getMessage();
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
            $this->ioController->writeLine(
                $e->getTraceAsString(),
                Verbosity::LEVEL_DEBUG
            );
            $exitCode = $e->getCode() == 0 ? 127 : $e->getCode();
        }
        if ($exitCode) {
            $message = 'Allure CLI has never successfully finished';
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
        }
        return $exitCode;
    }

    /**
     * Locates Allure executable.
     *
     * @throws AllureExecutableNotFoundException Thrown in case Runner couldn't
     *                                           find Allure executable.
     *
     * @return string Allure executable sting (either path to single executable
     *                file or `<path/to/java> -jar <path/to/jar>` string).
     * @since 0.1.0
     */
    private function getExecutable()
    {
        if ($command = $this->allureResolver->getAllureExecutable()) {
            return $command;
        }
        throw new AllureExecutableNotFoundException;
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
