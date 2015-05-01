<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
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
     * I/O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * Output formatter.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputFormatter;
    /**
     * Process instance.
     *
     * @type Process
     * @since 0.1.0
     */
    private $process;
    /**
     * Combines output.
     *
     * @type string
     * @since 0.1.0
     */
    private $output = '';


    /**
     * Initializer
     *
     * @param Process               $process      Process to run.
     * @param IOControllerInterface $ioController I\O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Process $process,
        IOControllerInterface $ioController = null
    ) {
        $this->process = $process;
        $this->ioController = $ioController;
        $this->outputFormatter = new OutputFormatter;
    }

    /**
     * Performs run.
     *
     * @return Process
     * @since 0.1.0
     */
    public function run()
    {
        $this->process->run(array($this, 'callback',));
        $this->output = trim($this->output);
        if ($this->process->getExitCode() !== 0) {
            return $this->process->getExitCode();
        }
        // added because Allure always returned exit code 0 up to v2.4
        $lines = explode("\n", $this->output);
        $result = trim(array_pop($lines));
        if (strpos($result, 'success') === false) {
            return 255;
        }
        return 0;
    }

    /**
     * Callback for process output.
     *
     * @param string $type
     * @param string $buffer
     *
     * @return void
     * @since 0.1.0
     */
    public function callback($type, $buffer)
    {
        $lines = $this->outputFormatter->formatOutput($buffer, $type);
        if ($this->ioController) {
            $verbosity = Verbosity::LEVEL_NOTICE;
            if ($type === Process::ERR) {
                $verbosity = Verbosity::LEVEL_DEBUG;
            }
            $this->ioController->writeLines($lines, $verbosity);
        }
        $this->output .= implode(PHP_EOL, $lines) . PHP_EOL;
    }

    /**
     * Returns run output.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutput()
    {
        return $this->output;
    }
}
