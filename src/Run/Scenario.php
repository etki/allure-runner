<?php

namespace Etki\Testing\AllureFramework\Runner\Run;

use Etki\Testing\AllureFramework\Runner\AllureCli\Runner;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\AllureExecutableResolver;
use Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\Cleaner;

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
     * Cleaner service.
     *
     * @type Cleaner
     * @since 0.1.0
     */
    private $cleaner;

    /**
     * Initializer.
     *
     * @param Configuration            $configuration  Allure runner
     *                                                 configuration.
     * @param AllureExecutableResolver $allureResolver Allure executable
     *                                                 resolver.
     * @param Runner                   $runner         Real CLI runner.
     * @param Cleaner                  $cleaner        Post-run cleaner service.
     * @param IOControllerInterface    $ioController   I/O controller.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        AllureExecutableResolver $allureResolver,
        Runner $runner,
        Cleaner $cleaner,
        IOControllerInterface $ioController
    ) {
        $this->configuration = $configuration;
        $this->allureResolver = $allureResolver;
        $this->runner = $runner;
        $this->cleaner = $cleaner;
        $this->ioController = $ioController;
    }

    /**
     * Runs scenario.
     *
     * @return Report Run report.
     * @since 0.1.0
     */
    public function run()
    {
        $executable = $this->allureResolver->getAllureExecutable();
        if (!$executable) {
            return $this->handleExecutableNotFoundCase();
        }
        $runOptions = $this->createRunOptions();
        $this->ioController->writeLine(
            'Invoking Allure',
            Verbosity::LEVEL_INFO
        );
        $report = $this->runner->run($executable, $runOptions);
        $this->ioController->writeLine(
            'Allure run finished',
            Verbosity::LEVEL_DEBUG
        );
        $this->handleRunResult($report);
        if ($this->configuration->shouldCleanGeneratedFiles()) {
            $this->cleaner->cleanUp();
        }
        return $report;
    }

    /**
     * Works out the case when Allure executable is missing,
     *
     * @return Report
     * @since 0.1.0
     */
    private function handleExecutableNotFoundCase()
    {
        $this->ioController->writeLine(
            'Couldn\'t find Allure executable',
            Verbosity::LEVEL_ERROR
        );
        $report = new Report(
            Report::STATUS_HALTED,
            null,
            null,
            null,
            new AllureExecutableNotFoundException
        );
        return $report;
    }

    /**
     * Performs post-run work.
     *
     * @param Report $report Report instance.
     *
     * @return void
     * @since 0.1.0
     */
    private function handleRunResult(Report $report)
    {
        if ($report->getStatus() !== Report::STATUS_SUCCESS) {
            $message = 'Allure CLI run has never successfully finished';
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
        }
        if ($exception = $report->getException()) {
            $message = sprintf(
                'Run halted with exception `%s`:',
                get_class($exception)
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
            $message = $exception->getMessage();
            $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
            $this->ioController->writeLine(
                $exception->getTraceAsString(),
                Verbosity::LEVEL_DEBUG
            );
        }
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
