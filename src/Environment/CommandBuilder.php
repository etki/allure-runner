<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

/**
 * THis class builds resulting command to run Allure.
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
    const COMMAND_TEMPLATE
        = '%s generate --report-path %s --report-version %s -- %s';

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
        $command = sprintf(
            self::COMMAND_TEMPLATE,
            $executable,
            $reportPath,
            $reportVersion,
            implode(' ', $sources)
        );
        return $command;
    }
}
