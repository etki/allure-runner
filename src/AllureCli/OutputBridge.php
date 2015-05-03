<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Symfony\Component\Process\Process;

/**
 * Output bridge that converts Allure output into I/O controller written lines.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class OutputBridge
{
    /**
     * Allure output formatter.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputFormatter;
    /**
     *
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * Allure CLI output.
     *
     * @type string
     * @since 0.1.0
     */
    private $output = '';

    /**
     * Initializer.
     *
     * @param OutputFormatter       $outputFormatter
     * @param IOControllerInterface $ioController
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        OutputFormatter $outputFormatter,
        IOControllerInterface $ioController
    ) {
        $this->outputFormatter = $outputFormatter;
        $this->ioController = $ioController;
    }

    /**
     * Does compute.
     *
     * @param string $type   Output type.
     * @param string $buffer Output chunk.
     *
     * @return void
     * @since 0.1.0
     */
    public function __invoke($type, $buffer)
    {
        $lines = $this->outputFormatter->formatOutput($buffer, $type);
        $verbosity = Verbosity::LEVEL_NOTICE;
        if ($type === Process::ERR) {
            $verbosity = Verbosity::LEVEL_DEBUG;
        }
        $this->ioController->writeLines($lines, $verbosity);
        $this->output .= $buffer;
    }

    /**
     * Returns recorded output.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Flushes current output, deleting all previous output and returning it's
     * copy.
     *
     * @return string
     * @since 0.1.0
     */
    public function flushOutput()
    {
        $output = $this->output;
        $this->output = '';
        return $output;
    }
}
