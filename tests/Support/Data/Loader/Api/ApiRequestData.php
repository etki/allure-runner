<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api;

/**
 * Simple request data wrapper.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api
 * @author  Etki <etki@etki.name>
 */
class ApiRequestData
{
    /**
     * URL response was fetched from.
     *
     * @type string
     * @since 0.1.0
     */
    private $url;

    /**
     * Initializer.
     *
     * @param string $url Request url.
     *
     * @since 0.1.0
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Returns url.
     *
     * @return string
     * @since 0.1.0
     */
    public function getUrl()
    {
        return $this->url;
    }
}
