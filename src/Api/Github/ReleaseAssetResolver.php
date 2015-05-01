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
     * API client instance.
     *
     * @type Client
     * @since 0.1.0
     */
    private $api;

    /**
     * Initializer.
     *
     * @param Client $githubApi Github API client instance.
     *
     * @since 0.1.0
     */
    public function __construct(Client $githubApi)
    {
        $this->api = $githubApi;
    }

    /**
     * Retrieves assets for specified release.
     *
     * @param string $tag Release name / version.
     *
     * @return array List of assets.
     * @since 0.1.0
     */
    public function getAssets($owner, $repository, $tag)
    {
        $release = $this->getRelease($owner, $repository, $tag);
        if (isset($release['assets'])) {
            return $release['assets'];
        }
        return array();
    }

    /**
     * Fetches specific release. If it doesn't exist, throws an exception.
     *
     * @param string $owner      Repository owner.
     * @param string $repository Repository name.
     * @param string $tag        Tag representing release.
     *
     * @throws ReleaseNotFoundException
     *
     * @return array Release data.
     * @since 0.1.0
     */
    private function getRelease($owner, $repository, $tag)
    {
        $releasesApi = $this->api->repos()->releases();
        $releases = $releasesApi->all($owner, $repository);
        foreach ($releases as $release) {
            if ($release['tag_name'] === $tag || $release['name'] === $tag) {
                return $release;
            }
        }
        foreach ($releases as $release) {
            if (strpos($release['tag_name'], $tag) !== false
                || strpos($release['name'], $tag !== false)
            ) {
                return $release;
            }
        }
        throw new ReleaseNotFoundException($tag);
    }
}
