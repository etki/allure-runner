<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api;

use DirectoryIterator;
use InvalidArgumentException;

/**
 * Loads API responses.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api
 * @author  Etki <etki@etki.name>
 */
class BaseApiResponseLoader
{
    /**
     * Directory in which response files reside.
     *
     * @type string
     * @since 0.1.0
     */
    private $baseDirectory;
    /**
     * List of responses,
     *
     * @type array
     * @since 0.1.0
     */
    private $responses = array();
    /**
     * List of responses sorted by tags.
     *
     * @type array
     * @since 0.1.0
     */
    private $tags = array();

    /**
     * Initializer.
     *
     * @param string $baseDirectory Directory in which releases are stored in.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * Retrieves particular response.
     *
     * @param string $method   Method name.
     * @param int    $sampleId Sample ID.
     *
     * @return BaseApiResponse
     * @since 0.1.0
     */
    public function getResponse($method, $sampleId)
    {
        $prefix = $this->baseDirectory . DIRECTORY_SEPARATOR;
        $responseFile = sprintf('%s%s.%s.json', $prefix, $method, $sampleId);
        $responseMetadataFile = sprintf(
            '%s%s.%s.metadata.json',
            $prefix,
            $method,
            $sampleId
        );
        $rawMetaData
            = json_decode(file_get_contents($responseMetadataFile), true);
        $metaData = new ApiResponseData(
            isset($rawMetaData['tags']) ? $rawMetaData['tags'] : array(),
            isset($rawMetaData['meta']) ? $rawMetaData['meta'] : array(),
            isset($rawMetaData['length']) ? $rawMetaData['length'] : null
        );
        $requestData = new ApiRequestData($rawMetaData['request']['url']);
        $response = new BaseApiResponse(
            json_decode(file_get_contents($responseFile), true),
            $metaData,
            $requestData
        );
        return $response;
    }

    /**
     * Retrieves responses for particular method-tag pair.
     *
     * @param string $method Method to get responses for.
     * @param string $tag    Tag.
     *
     * @return BaseApiResponse[]
     * @since 0.1.0
     */
    public function getResponses($method, $tag)
    {
        if (!$this->responses) {
            $this->loadResponses();
        }
        if (!isset($this->tags[$tag])) {
            throw new InvalidArgumentException(sprintf('Unknown tag %s', $tag));
        }
        return $this->tags[$tag][$method];
    }

    /**
     * Returns list of responses in [method => [id list]] format.
     *
     * @return int[][]
     * @since 0.1.0
     */
    private function listResponses()
    {
        $responses = array();
        $pattern = '~(.*)\.(\d+)\.json~ui';
        foreach (new DirectoryIterator($this->baseDirectory) as $node) {
            $filename = $node->getFilename();
            if (!$node->isFile() || !preg_match($pattern, $filename, $matches)) {
                continue;
            }
            list($method, $id) = array_slice($matches, 1);
            if (!isset($responses[$method])) {
                $responses[$method] = array();
            }
            $responses[$method][] = (int) $id;
        }
        return $responses;
    }

    /**
     * Loads responses from hard drive.
     *
     * @return void
     * @since 0.1.0
     */
    private function loadResponses()
    {
        foreach ($this->listResponses() as $method => $idList) {
            foreach ($idList as $id) {
                if (!isset($this->responses[$method])) {
                    $this->responses[$method] = array();
                }
                /** @type BaseApiResponse $response */
                $response = $this->getResponse($method, $id);
                $this->responses[$method][$id] = $response;
                foreach ($response->getMeta()->getTags() as $tag) {
                    if (!isset($this->tags[$tag])) {
                        $this->tags[$tag] = array();
                    }
                    if (!isset($this->tags[$tag][$method])) {
                        $this->tags[$tag][$method] = array();
                    }
                    $this->tags[$tag][$method][] = $response;
                }
            }
        }
    }
}
