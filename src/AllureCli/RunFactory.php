<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;

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
     * Factory for producing STDOUT bridges from Allure to I/O controller.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputBridgeFactory;
    /**
     * PHP API instance.
     *
     * @type PhpApi
     * @since 0.1.0
     */
    private $phpApi;

    /**
     * Initializer.
     *
     * @param ProcessFactory      $processFactory Process factory.
     * @param OutputBridgeFactory $bridgeFactory  Output bridge generator.
     * @param ResultOutputParser  $outputParser   Allure output parser.
     * @param PhpApi              $phpApi         PHP API instance.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        ProcessFactory $processFactory,
        OutputBridgeFactory $bridgeFactory,
        PhpApi $phpApi
    ) {
        $this->processFactory = $processFactory;
        $this->outputBridgeFactory = $bridgeFactory;
        $this->phpApi = $phpApi;
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
            $this->phpApi
        );
        return $run;
    }
}
