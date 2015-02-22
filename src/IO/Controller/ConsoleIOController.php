<?php

namespace Etki\Testing\AllureFramework\Runner\IO\Controller;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;
use Etki\Testing\AllureFramework\Runner\IO\WriterInterface;
use Etki\Testing\AllureFramework\Runner\Runner;
use DateTime;

/**
 * Basic console I\O controller.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
class ConsoleIOController extends AbstractIOController implements
    PrefixAwareIOControllerInterface
{
    /**
     * Default prefix format.
     *
     * @since 0.1.0
     */
    const DEFAULT_PREFIX_FORMAT
        = PrefixAwareIOControllerInterface::FULL_PREFIX_FORMAT;
    /**
     * Writer instance.
     *
     * @type WriterInterface
     * @since 0.1.0
     */
    private $writer;
    /**
     * Simple flag that indicates that last message ended with EOL character.
     *
     * @type bool
     * @since
     */
    private $lastMessageEndedWithEol = true;

    /**
     * Format of a prefix that will be inserted on every new line.
     *
     * @type string
     * @since 0.1.0
     */
    private $prefixFormat = self::DEFAULT_PREFIX_FORMAT;

    /**
     * Initializer.
     *
     * @param WriterInterface $writer    Writer instance.
     * @param string          $verbosity Verbosity level.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        WriterInterface $writer,
        $verbosity = Verbosity::LEVEL_INFO
    ) {
        $this->writer = $writer;
        $this->setVerbosity($verbosity);
    }

    /**
     * Sets new prefix format. Currently supported placeholders: `{date}`,
     * `{time}`, `{dateTime}`, `{verbosity}`, `{softwareName}`.
     *
     * @param string $prefixFormat New prefix formatting string.
     *
     * @return void
     * @since 0.1.0
     */
    public function setPrefixFormat($prefixFormat)
    {
        $this->prefixFormat = $prefixFormat;
    }
    
    /**
     * Writes message.
     *
     * @param string $message   Message to write.
     * @param string $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message, $verbosity = Verbosity::LEVEL_INFO)
    {
        if ($this->isAllowedVerbosityLevel($verbosity)) {
            if ($this->lastMessageEndedWithEol) {
                $this->writeMessagePrefix($verbosity);
            }
            $this->writer->write($message);
            $this->lastMessageEndedWithEol
                = $this->endsWithEol($message) || !$message;
        }
    }
    /**
     * Writes single line.
     *
     * @param string $message   Message to output.
     * @param string $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function writeLine(
        $message = '',
        $verbosity = Verbosity::LEVEL_INFO
    ) {
        if (!$this->isAllowedVerbosityLevel($verbosity)) {
            return;
        }
        if ($this->lastMessageEndedWithEol) {
            $this->writeMessagePrefix($verbosity);
        }
        if (strrpos($message, PHP_EOL) !== strlen($message) - strlen(PHP_EOL)) {
            $message .= PHP_EOL;
        }
        $this->writer->write($message);
        $this->lastMessageEndedWithEol = true;
    }

    /**
     * Tells if message ends with EOL character.
     *
     * @param string $message Message to analyze.
     *
     * @return bool
     * @since 0.1.0
     */
    private function endsWithEol($message)
    {
        $expectedPosition = strlen($message) - strlen(PHP_EOL);
        return strrpos($message, PHP_EOL) === $expectedPosition;
    }

    /**
     * Outputs message prefix.
     *
     * @param string $verbosity Current verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    private function writeMessagePrefix($verbosity)
    {
        $now = new DateTime;
        $verbosity = strtoupper(str_pad($verbosity, 9, ' '));
        $replacements = array(
            '{date}' => $now->format('Y-m-d'),
            '{time}' => $now->format('H:i:s'),
            '{dateTime}' => $now->format('Y-m-d H:i:s'),
            '{softwareName}' => Runner::SOFTWARE_NAME,
            '{software}' => Runner::SOFTWARE_NAME,
            '{verbosity}' => $verbosity,
        );
        $prefix = str_replace(
            array_keys($replacements),
            $replacements,
            $this->prefixFormat
        );
        if ($prefix) {
            $prefix .= ' ';
        }
        $this->writer->write($prefix);
    }
}
