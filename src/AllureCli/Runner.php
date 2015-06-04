<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Run\Report;
use Exception;

/**
 * The real allure runner, no kidding.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class Runner
{
    /**
     * Name of the software.
     *
     * @since 0.1.0
     */
    const SOFTWARE_NAME = 'Allure CLI';

    /**
     * Run process factory instance.
     *
     * @type RunFactory
     * @since 0.1.0
     */
    private $runFactory;
    /**
     * Command builder factory.
     *
     * @type CommandBuilderFactory
     * @since 0.1.0
     */
    private $commandBuilderFactory;
    /**
     * Parser that helps to determine if run has really been successful.
     *
     * @type ResultOutputParser
     * @since 0.1.0
     */
    private $outputParser;
    /**
     * I/O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param RunFactory            $runFactory            Run process factory
     *                                                     instance.
     * @param CommandBuilderFactory $commandBuilderFactory Command builder
     *                                                     factory.
     * @param ResultOutputParser    $outputParser          Allure output parser
     *                                                     that helps determine
     *                                                     run success.
     * @param IOControllerInterface $ioController          I/O controller.
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @since 0.1.0
     */
    public function __construct(
        RunFactory $runFactory,
        CommandBuilderFactory $commandBuilderFactory,
        ResultOutputParser $outputParser,
        IOControllerInterface $ioController
    ) {
        $this->runFactory = $runFactory;
        $this->commandBuilderFactory = $commandBuilderFactory;
        $this->outputParser = $outputParser;
        $this->ioController = $ioController;
    }

    /**
     * Performs Allure run.
     *
     * @param string     $executable Path to executable.
     * @param RunOptions $options    Run options.
     *
     * @return Report
     * @since 0.1.0
     */
    public function run(
        $executable,
        RunOptions $options
    ) {
        $command = $this->prepareCommand($executable, $options);
        $run = $this->runFactory->getRun($command);
        return $this->performRun($run);
    }

    /**
     * Builds command.
     *
     * @param string     $executable Path to executable.
     * @param RunOptions $options    Run options.
     *
     * @return string
     * @since 0.1.0
     */
    private function prepareCommand($executable, RunOptions $options)
    {
        $command = $this
            ->commandBuilderFactory
            ->getCommandBuilder($executable, 'generate')
            ->addOptions(
                array(
                    'report-path' => $options->getReportPath(),
                    'report-version' => $options->getReportVersion(),
                )
            )
            ->addPostArguments($options->getSources())
            ->getCommand();
        $this->ioController->writeLine(
            'Prepared allure command: ' . $command,
            Verbosity::LEVEL_INFO
        );
        return $command;
    }

    /**
     * Performs run.
     *
     * @param Run $run Run object.
     *
     * @return Report
     * @since 0.1.0
     */
    private function performRun(Run $run)
    {
        $exception = null;
        try {
            $run->run();
            $parser = $this->outputParser;
            if ($run->getExitCode() === 0
                && $parser->isSuccessfulRun($run->getOutput()) !== false
            ) {
                $status = Report::STATUS_SUCCESS;
            } else {
                $status = Report::STATUS_FAIL;
            }
        } catch (Exception $exception) {
            $status = Report::STATUS_HALTED;
        }
        $report = new Report(
            $status,
            $run->getExitCode(),
            $run->getOutput(),
            $run->getRunningTime(),
            $exception
        );
        return $report;
    }
}
