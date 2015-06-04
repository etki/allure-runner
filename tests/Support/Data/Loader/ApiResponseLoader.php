<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader;

use Codeception\Configuration;
use InvalidArgumentException;

/**
 * Loads API responses.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader
 * @author  Etki <etki@etki.name>
 */
class ApiResponseLoader
{
    /**
     * Relative path to api data directory.
     *
     * @since 0.1.0
     */
    const DATA_DIRECTORY_PATH = 'Samples/Api';
    /**
     * List of known APIs.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $api = array(
        'github' => 'Github',
    );

    /**
     * Retrieves particular API response.
     *
     * @param string $api          API name.
     * @param string $method       API method.
     * @param int    $sampleNumber ID of the sample to use.
     *
     * @return array Response data.
     * @since 0.1.0
     */
    public function getResponse($api, $method, $sampleNumber = 1)
    {
        if (!isset($this->api[$api])) {
            $message = sprintf('Unknown API `%s`', $api);
            throw new InvalidArgumentException($message);
        }
        $root = $this->getRoot();
        $method = $this->sanitizeMethod($method);
        $chunks = array($root, $this->api[$api], '');
        $prefix = implode(DIRECTORY_SEPARATOR, $chunks);
        $path = sprintf('%s.%s.json', $prefix . $method, $sampleNumber);
        $metadataPath = sprintf(
            '%s.%s.metadata.json',
            $prefix . $method,
            $sampleNumber
        );
        $response = json_decode(file_get_contents($path), true);
        $metadata = json_decode(file_get_contents($metadataPath), true);
        
        return array('response' => $response, 'metadata' => $metadata,);
    }

    /**
     * Converts method URI into filename.
     *
     * @param string $method Method.
     *
     * @return string
     * @since 0.1.0
     */
    private function sanitizeMethod($method)
    {
        return trim(str_replace('/', '', $method), '.');
    }

    /**
     * Returns API storage root.
     *
     * @return string
     * @since 0.1.0
     */
    private function getRoot()
    {
        $dataDirectory = rtrim(Configuration::dataDir(), '\\/');
        return $dataDirectory . DIRECTORY_SEPARATOR . self::DATA_DIRECTORY_PATH;
    }
}
