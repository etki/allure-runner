<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

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
     * Command builder.
     *
     * @type CommandBuilder
     * @since 0.1.0
     */
    private $commandBuilder;

    /**
     * Initializer.
     *
     * @param RunFactory     $runFactory     Run process factory instance.
     * @param CommandBuilder $commandBuilder Command builder.
     *
     * @since 0.1.0
     */
    public function __construct(
        RunFactory $runFactory,
        CommandBuilder $commandBuilder
    ) {
        $this->runFactory = $runFactory;
        $this->commandBuilder = $commandBuilder;
    }

    /**
     * Performs Allure run.
     *
     * @param string                $executable   Path to executable.
     * @param RunOptions            $options      Run options.
     *
     * @return int
     * @since 0.1.0
     */
    public function run(
        $executable,
        RunOptions $options
    ) {
        $command = $this->commandBuilder->buildGenerateCommand(
            $executable,
            $options->getSources(),
            $options->getReportPath(),
            $options->getReportVersion()
        );
        $run = $this->runFactory->getRun($command);
        return $run->run();
    }
}
