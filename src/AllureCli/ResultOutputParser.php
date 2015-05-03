<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

/**
 * Parses Allure output to determine operation success.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class ResultOutputParser
{
    /**
     * Detects if run was successful using Allure CLI output.
     *
     * @param string $output        Allure CLI output.
     * @param string $allureVersion Allure version. Currently unused.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return bool|null True for successful run, false for unsuccessful or null
     * if can't determine.
     * @since 0.1.0
     */
    public function isSuccessfulRun($output, $allureVersion = null)
    {
        return (bool) preg_match('~\bsuccessfully\b~iu', $output);
    }
}
