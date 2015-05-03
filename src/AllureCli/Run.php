<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
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
     * Combines output.
     *
     * @type string
     * @since 0.1.0
     */
    private $output;


    /**
     * Initializer
     *
     * @param Process               $process      Process to run.
     * @param OutputBridge          $outputBridge Bridge that feeds Allure
     *                                            output into I/O controller.
     * @param ResultOutputParser    $outputParser Output parser instance that
     *                                            helps in detecting operation
     *                                            success.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Process $process,
        OutputBridge $outputBridge,
        ResultOutputParser $outputParser
    ) {
        $this->process = $process;
        $this->outputBridge = $outputBridge;
        $this->outputParser = $outputParser;
    }

    /**
     * Performs run.
     *
     * @return Process
     * @since 0.1.0
     */
    public function run()
    {
        $this->process->run($this->outputBridge);
        $this->output = trim($this->outputBridge->getOutput());
        if ($this->process->getExitCode() !== 0) {
            return $this->process->getExitCode();
        }
        // added because Allure always returned exit code 0 up to v2.4
        if ($this->outputParser->isSuccessfulRun($this->output) === false) {
            return Configuration::GENERIC_ERROR_EXIT_CODE;
        }
        return 0;
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
}
