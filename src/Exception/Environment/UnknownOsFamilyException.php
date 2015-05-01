<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Environment;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;
use Exception;

/**
 * Designed to be thrown whenever action can't be taken because of unknown OS.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Environment
 * @author  Etki <etki@etki.name>
 */
class UnknownOsFamilyException extends RuntimeException
{
    /**
     * OS family unknown to runner.
     *
     * @type string
     * @since 0.1.0
     */
    private $osFamily;

    /**
     * Initializer.
     *
     * @param string    $osFamily Operating system family.
     * @param string    $message  Exception message.
     * @param Exception $previous Previous exception.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $osFamily,
        $message = null,
        Exception $previous = null
    ) {
        $this->osFamily = $osFamily;
        if (!$message) {
            $message = sprintf('Unknown OS family met: `%s`', $osFamily);
        }
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns OS family not known by runner.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getOsFamily()
    {
        return $this->osFamily;
    }
}
