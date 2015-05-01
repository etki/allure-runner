<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Environment\CommandBuilder;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

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
     * Process factory instance.
     *
     * @type ProcessFactory
     * @since 0.1.0
     */
    private $processFactory;

    /**
     * Initializer.
     *
     * @param ProcessFactory $processFactory Process factory instance.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(ProcessFactory $processFactory)
    {
        $this->processFactory = $processFactory;
    }

    /**
     * Performs Allure run.
     *
     * @param string                $executable   Path to executable.
     * @param RunOptions            $options      Run options.
     * @param IOControllerInterface $ioController I\O controller.
     *
     * @return int
     * @since 0.1.0
     */
    public function run(
        $executable,
        RunOptions $options,
        IOControllerInterface $ioController = null
    ) {
        $builder = new CommandBuilder;
        $command = $builder->buildGenerateCommand(
            $executable,
            $options->getSources(),
            $options->getReportPath(),
            $options->getReportVersion()
        );
        $process = $this->processFactory->getProcess($command);
        $run = new Run($process, $ioController);
        return $run->run();
    }
}
