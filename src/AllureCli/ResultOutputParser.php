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
     * @param string $output Allure CLI output.
     *
     * @return bool|null True for successful run, false for unsuccessful or null
     * if can't determine.
     * @since 0.1.0
     */
    public function detectSuccess($output)
    {
        return (bool) preg_match('~\bsuccessfully\b~', $output);
    }
}
