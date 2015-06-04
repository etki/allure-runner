<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * This factory provides bridge objects.
 *
 * todo shouldn't output formatter and io controller be provided during the
 * creation?
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class OutputBridgeFactory
{
    /**
     * Allure CLI output formatter.
     *
     * @type OutputFormatter
     * @since 0.1.0
     */
    private $outputFormatter;
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
     * @param OutputFormatter       $outputFormatter Output formatter.
     * @param IOControllerInterface $ioController    I/O controller.
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
     * Creates new bridge.
     *
     * @codeCoverageIgnore
     *
     * @return OutputBridge
     * @since 0.1.0
     */
    public function getBridge()
    {
        return new OutputBridge($this->outputFormatter, $this->ioController);
    }
}
