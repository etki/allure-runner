<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

/**
 * THis class builds resulting command to run Allure.
 *
 * // todo refactor
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class CommandBuilder
{
    /**
     *  Template for generating command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_GENERATE = '%s generate %s';

    /**
     * Builds command
     *
     * @param string   $executable    Path/command that will launch Allure.
     * @param string[] $sources       List of data sources to generate report.
     * @param string   $reportPath    Directory to put report into.
     * @param string   $reportVersion Report version to use.
     *
     * @return string Built command.
     * @since 0.1.0
     */
    public function buildGenerateCommand(
        $executable,
        array $sources,
        $reportPath,
        $reportVersion
    ) {
        $arguments = array(
            'report-path' => $reportPath,
            'report-version' => $reportVersion,
        );
        $formattedArguments = array();
        foreach ($arguments as $name => $value) {
            if ($value) {
                $formattedArguments[] = sprintf(
                    '--%s %s',
                    $name,
                    $this->sanitizeArgumentValue($value)
                );
            }
        }
        foreach ($sources as &$source) {
            $source = $this->sanitizeArgumentValue($source);
        }
        $argumentList = array_merge(
            $formattedArguments,
            array('--', implode(' ', $sources),)
        );
        $command = sprintf(
            self::COMMAND_TEMPLATE_GENERATE,
            $executable,
            implode(' ', $argumentList)
        );
        return $command;
    }

    /**
     * Sanitizes argument value.
     *
     * @param string $value
     *
     * @return string
     * @since 0.1.0
     */
    public function sanitizeArgumentValue($value)
    {
        if (preg_match('~\s~u', $value)) {
            $value = sprintf('"%s"', $value);
        }
        return $value;
    }
}
