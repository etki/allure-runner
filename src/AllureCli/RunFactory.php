<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;

/**
 * Produces Allure Run objects responsible for real Allure runs.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class RunFactory
{
    /**
     * Process factory.
     *
     * @type ProcessFactory
     * @since 0.1.0
     */
    private $processFactory;
    /**
     * Parser for Allure output that detects run success.
     *
     * @type ResultOutputParser
     * @since 0.1.0
     */
    private $outputParser;
    /**
     * Factory for producing STDOUT bridges from Allure to I/O controller.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputBridgeFactory;

    /**
     * Initializer.
     *
     * @param ProcessFactory      $processFactory Process factory.
     * @param OutputBridgeFactory $bridgeFactory  Output bridge generator.
     * @param ResultOutputParser  $outputParser   Allure output parser.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        ProcessFactory $processFactory,
        OutputBridgeFactory $bridgeFactory,
        ResultOutputParser $outputParser
    ) {
        $this->processFactory = $processFactory;
        $this->outputBridgeFactory = $bridgeFactory;
        $this->outputParser = $outputParser;
    }

    /**
     * Creates Run object.
     *
     * @param string $command Command to run.
     *
     * @codeCoverageIgnore
     *
     * @return Run
     * @since 0.1.0
     */
    public function getRun($command)
    {
        $process = $this->processFactory->getProcess($command);
        $run = new Run(
            $process,
            $this->outputBridgeFactory->getBridge(),
            $this->outputParser
        );
        return $run;
    }
}
