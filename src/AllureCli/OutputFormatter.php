<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

/**
 * Simple formatter that parses Allure output and creates messages ready to be
 * passed to I\O controller.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class OutputFormatter
{
    /**
     * Default prefix format.
     *
     * @since 0.1.0
     */
    const DEFAULT_PREFIX_FORMAT = '[{software}] [{stream}]';
    /**
     * Format of the message prefix.
     *
     * @type string
     * @since 0.1.0
     */
    private $prefixFormat;
    /**
     * {software} key replacement.
     *
     * @type string
     * @since 0.1.0
     */
    private $softwareName;

    /**
     * Sets new prefix format.
     *
     * @param string $prefixFormat New prefix format to be set.
     *
     * @return void
     * @since 0.1.0
     */
    public function setPrefixFormat($prefixFormat)
    {
        $this->prefixFormat = $prefixFormat;
    }

    /**
     * Sets new software name.
     *
     * @param string $softwareName Software name to set.
     *
     * @return void
     * @since 0.1.0
     */
    public function setSoftwareName($softwareName)
    {
        $this->softwareName = $softwareName;
    }

    /**
     * Formats direct Allure output.
     *
     * @param string $output Output buffer.
     * @param string $stream Stream name.
     *
     * @return string[] List of messages ready to be output.
     * @since 0.1.0
     */
    public function formatOutput($output, $stream)
    {
        $rawLines = array_map('rtrim', explode("\n", $output));
        $lines = array();
        foreach ($rawLines as &$line) {
            if (!$line) {
                continue;
            }
            if ($prefix = $this->getPrefix($stream)) {
                $line = $prefix . ' ' . $line;
            }
            $lines[] = $line;
        }
        return $lines;
    }

    /**
     * Returns prefix for message. Currently supported replacements are
     * {software} and {stream}.
     *
     * @param $stream
     *
     * @return string|null
     * @since 0.1.0
     */
    private function getPrefix($stream)
    {
        if (!$this->prefixFormat) {
            return null;
        }
        $replacements = array(
            '{software}' => $this->softwareName,
            '{stream}' => $stream,
        );
        $prefix = str_replace(
            array_keys($replacements),
            $replacements,
            $this->prefixFormat
        );
        return $prefix;
    }
}
