<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Api\Github;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;
use Exception;

/**
 * Designed to be thrown whenever release is not found.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseNotFoundException extends RuntimeException
{
    /**
     * Used release tag.
     *
     * @type string
     * @since 0.1.0
     */
    private $tag;

    /**
     * Initializer.
     *
     * @param string    $tag      Used release tag.
     * @param string    $message  Exception message.
     * @param Exception $previous Previous exception.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $tag,
        $message = null,
        Exception $previous = null
    ) {
        $this->tag = $tag;
        if (!$message) {
            $message = sprintf('Release `%s` could not be found', $tag);
        }
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns release tag.
     *
     * @return string
     * @since 0.1.0
     */
    public function getReleaseTag()
    {
        return $this->tag;
    }
}
