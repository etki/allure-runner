<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api;

/**
 * API response.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api
 * @author  Etki <etki@etki.name>
 */
class BaseApiResponse
{
    /**
     * Response data.
     *
     * @type mixed
     * @since 0.1.0
     */
    private $data;
    /**
     * Data about request used to retrieve current response.
     *
     * @type ApiRequestData
     * @since 0.1.0
     */
    private $request;
    /**
     * Metadata.
     *
     * @type ApiResponseData
     * @since 0.1.0
     */
    private $meta;

    /**
     * Initializer.
     *
     * @param mixed           $data
     * @param ApiResponseData $meta
     * @param ApiRequestData  $request
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $data,
        ApiResponseData $meta,
        ApiRequestData $request
    ) {
        $this->data = $data;
        $this->meta = $meta;
        $this->request = $request;
    }

    /**
     * Returns data.
     *
     * @return mixed
     * @since 0.1.0
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns meta.
     *
     * @return ApiResponseData
     * @since 0.1.0
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Returns request.
     *
     * @return ApiRequestData
     * @since 0.1.0
     */
    public function getRequest()
    {
        return $this->request;
    }
}
