<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api;

/**
 * Response metadata.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api
 * @author  Etki <etki@etki.name>
 */
class ApiResponseData
{
    /**
     * Freeform response data.
     *
     * @type array
     * @since 0.1.0
     */
    private $freeFormData;
    /**
     * List of response tags.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $tags = array();
    /**
     * Response length (in items).
     *
     * @type int
     * @since 0.1.0
     */
    private $length;

    /**
     * Initializer.
     *
     * @param string[] $tags         List of tags.
     * @param array    $freeFormData Freeform data.
     * @param int      $length       Response length.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        array $tags = array(),
        array $freeFormData = array(),
        $length = null
    ) {
        $this->tags = $tags;
        $this->freeFormData = $freeFormData;
        $this->length = $length;
    }

    /**
     * Returns freeFormData.
     *
     * @return array
     * @since 0.1.0
     */
    public function getFreeFormData()
    {
        return $this->freeFormData;
    }

    /**
     * Returns response length.
     *
     * @return int
     * @since 0.1.0
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Returns tags.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Retrieves freeform data by path
     *
     * @param string $path         Dot-delimited keys.
     * @param null   $defaultValue Value to return if item hasn't been found.
     *
     * @return mixed
     * @since 0.1.0
     */
    public function tryGetFreeFormDataItem($path, $defaultValue = null)
    {
        $source = $this->freeFormData;
        $keys = explode('.', $path);
        while ($keys && isset($source[reset($keys)])) {
            $source = $source[array_pop($keys)];
        }
        if ($keys) {
            return $defaultValue;
        }
        return $source;
    }
}
