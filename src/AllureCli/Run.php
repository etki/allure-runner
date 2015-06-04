<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Symfony\Component\Process\Process;

/**
 * This class represents single Allure run.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class Run
{
    /**
     * Output formatter.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputBridge;
    /**
     * Process instance.
     *
     * @type Process
     * @since 0.1.0
     */
    private $process;
    /**
     * PHP API instance.
     *
     * @type PhpApi
     * @since 0.1.0
     */
    private $phpApi;
    /**
     * Combines output.
     *
     * @type string
     * @since 0.1.0
     */
    private $output;
    /**
     * Start time.
     *
     * @type float
     * @since 0.1.0
     */
    private $startTime;
    /**
     * End time.
     *
     * @type float
     * @since 0.1.0
     */
    private $endTime;
    /**
     * Process exit code.
     *
     * @type int
     * @since 0.1.0
     */
    private $exitCode;

    /**
     * Initializer.
     *
     * @param Process               $process      Process to run.
     * @param OutputBridge          $outputBridge Bridge that feeds Allure
     *                                            output into I/O controller.
     * @param PhpApi                $phpApi       PHP API instance.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Process $process,
        OutputBridge $outputBridge,
        PhpApi $phpApi
    ) {
        $this->process = $process;
        $this->outputBridge = $outputBridge;
        $this->phpApi = $phpApi;
    }

    /**
     * Performs run.
     *
     * @return Process
     * @since 0.1.0
     */
    public function run()
    {
        $this->startTime = $this->phpApi->getTime();
        $this->process->run($this->outputBridge);
        $this->endTime = $this->phpApi->getTime();
        $this->output = trim($this->outputBridge->getOutput());
        $this->exitCode = $this->process->getExitCode();
    }

    /**
     * Returns run output.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Returns start time.
     *
     * @return float
     * @since 0.1.0
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Returns end time.
     *
     * @return float
     * @since 0.1.0
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Returns running time.
     *
     * @return float
     * @since 0.1.0
     */
    public function getRunningTime()
    {
        return $this->endTime - $this->startTime;
    }

    /**
     * Returns process exit code.
     *
     * @return int
     * @since 0.1.0
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
