<?php

namespace Etki\Testing\AllureFramework\Runner\Adapter\Codeception;

use Etki\Testing\AllureFramework\Runner\Adapter\Codeception\Configuration\Builder;
use Etki\Testing\AllureFramework\Runner\Adapter\PHPUnit\IO\Writer\PrinterWrapper;
use Etki\Testing\AllureFramework\Runner\IO\Controller\ConsoleIOController;
use Etki\Testing\AllureFramework\Runner\Runner;
use Codeception\Platform\Extension as CodeceptionExtension;
use Codeception\Event\PrintResultEvent;

/**
 * Extension class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Adapter\Codeception
 * @author  Etki <etki@etki.name>
 */
class Extension extends CodeceptionExtension
{
    /**
     * List of events to subscribe to.
     *
     * @type string[]
     * @since 0.1.0
     */
    public static $events = array(
        'result.print.after' => 'generateReport',
    );

    /**
     * Generates report.
     *
     * @param PrintResultEvent $event Event to process.
     *
     * @return void
     * @since 0.1.0
     */
    public function generateReport(PrintResultEvent $event)
    {
        if ($event->getResult()->count() > 1) {
            // todo make a configuration option
        }
        $printer = $event->getPrinter();
        $writer = new PrinterWrapper($printer);
        $ioController = new ConsoleIOController($writer);
        $configurationBuilder = new Builder;
        $configuration = $configurationBuilder->build($this->config);
        $runner = new Runner($configuration, $ioController);
        $runner->run();
    }
}
