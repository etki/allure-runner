<?php

namespace Etki\Testing\AllureFramework\Runner\Run;

use Exception;

/**
 * Simple run report.
 *
 * @codeCoverageIgnore Data Transfer Object.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run
 * @author  Etki <etki@etki.name>
 */
class Report
{
    /**
     * Status for successful run.
     *
     * @since 0.1.0
     */
    const STATUS_SUCCESS = 'success';
    /**
     * Status for failed run.
     *
     * @since 0.1.0
     */
    const STATUS_FAIL = 'fail';
    /**
     * Status for run halted due to exception or anything else.
     *
     * @since 0.1.0
     */
    const STATUS_HALTED = 'halted';
    /**
     * Status for run that has been cancelled before start.
     *
     * @since 0.1.0
     */
    const STATUS_CANCELLED = 'cancelled';
    /**
     * Running time in seconds or null if cancelled.
     *
     * @type float|null
     * @since 0.1.0
     */
    private $runningTime;
    /**
     * Run status.
     *
     * @type string
     * @since 0.1.0
     */
    private $status;
    /**
     * Allure output.
     *
     * @type string
     * @since 0.1.0
     */
    private $output;
    /**
     * Exception, if occurred.
     *
     * @type Exception
     * @since 0.1.0
     */
    private $exception;
    /**
     * Run exit code.
     *
     * @type int
     * @since 0.1.0
     */
    private $exitCode;

    /**
     * Initializer.
     *
     * @param string    $status      Status to set.
     * @param int       $exitCode    Process exit code.
     * @param string    $output      Allure output.
     * @param float     $runningTime Allure running time.
     * @param Exception $exception   Exception (if any occurred)
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $status,
        $exitCode = null,
        $output = null,
        $runningTime = null,
        Exception $exception = null
    ) {
        $this->status = $status;
        $this->exitCode = $exitCode;
        $this->output = $output;
        $this->runningTime = $runningTime;
        $this->exception = $exception;
    }

    /**
     * Returns run status.
     *
     * @return string
     * @since 0.1.0
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns process exit code.
     *
     * @return int|null
     * @since 0.1.0
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Returns running time.
     *
     * @return float|null
     * @since 0.1.0
     */
    public function getRunningTime()
    {
        return $this->runningTime;
    }

    /**
     * Returns exception.
     *
     * @return Exception
     * @since 0.1.0
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Returns output.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutput()
    {
        return $this->output;
    }
}
