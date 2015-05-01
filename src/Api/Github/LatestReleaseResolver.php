<?php

namespace Etki\Testing\AllureFramework\Runner\Api\Github;

use DateTime;
use Github\Client as GithubApiClient;

/**
 * Finds latest release.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Api\Github
 * @author  Etki <etki@etki.name>
 */
class LatestReleaseResolver
{
    /**
     * Github API client instance.
     *
     * @type GithubApiClient
     * @since 0.1.0
     */
    private $api;

    /**
     * Initializer.
     *
     * @param GithubApiClient $api API client instance.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(GithubApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Retrieves latest release.
     *
     * @param string $owner Owner username/orgname.
     * @param string $repo  Repository name.
     *
     * @return array|null Release data or null if no releases found.
     * @since 0.1.0
     */
    public function getLatestRelease($owner, $repo)
    {
        $releases = $this->api->repos()->releases()->all($owner, $repo);
        if (!$releases) {
            return null;
        }
        usort($releases, array($this, 'releaseSortCallback',));
        return $releases[0];
    }

    /**
     * Compare two releases in usort-compatible manner.
     *
     * @param array $releaseA Release A.
     * @param array $releaseB Release B.
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @return int
     * @since 0.1.0
     */
    private function releaseSortCallback($releaseA, $releaseB)
    {
        if ($releaseA['prerelease'] === !$releaseB['prerelease']) {
            return $releaseA['prerelease'] ? 1 : -1;
        }
        $releaseADate = new DateTime($releaseA['published_at']);
        $releaseBDate = new DateTime($releaseB['published_at']);
        return $releaseADate->getTimestamp() - $releaseBDate->getTimestamp();
    }
}
