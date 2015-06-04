<?php

namespace Etki\Testing\AllureFramework\Runner\Api\Github;

use Etki\Testing\AllureFramework\Runner\Exception\Api\Github\ReleaseNotFoundException;
use Github\Client;

/**
 * This class returns assets for specified release.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseAssetResolver
{
    /**
     * Retrieves URL of first zip asset.
     *
     * @param array $release Release definition as provided by API.
     *
     * @return string|null Asset URL or null.
     * @since 0.1.0
     */
    public function getFirstZipAssetUrl($release)
    {
        $assets = !empty($release['assets']) ? $release['assets'] : array();
        foreach ($assets as $asset) {
            if ($asset['content_type'] === 'application/zip'
                && !empty($asset['browser_download_url'])
            ) {
                return $asset['browser_download_url'];
            }
        }
        return null;
    }
}
