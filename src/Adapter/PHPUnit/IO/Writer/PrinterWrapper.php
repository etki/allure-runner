<?php

namespace Etki\Testing\AllureFramework\Runner\Adapter\PHPUnit\IO\Writer;

use Etki\Testing\AllureFramework\Runner\IO\WriterInterface;
use PHPUnit_Util_Printer as Printer;

/**
 * Simple PHPUnit utility printer wrapper.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Adapter\PHPUnit\IO\Writer
 * @author  Etki <etki@etki.name>
 */
class PrinterWrapper implements WriterInterface
{
    /**
     * Printer Instance
     *
     * @type Printer
     * @since 0.1.0
     */
    private $printer;

    /**
     * Initializer.
     *
     * @param Printer $printer Printer instance.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    /**
     * Writes single message on screen.
     *
     * @param string $message Message to write.
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message)
    {
        $this->printer->write($message);
    }
}
