<?php

namespace Etki\Testing\AllureFramework\Runner\Api\Github;

use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseCollectionManipulator
    as CollectionManipulator;
use Github\Client as GithubApiClient;

/**
 * Finds latest release.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseResolver
{
    /**
     * Github API client instance.
     *
     * @type GithubApiClient
     * @since 0.1.0
     */
    private $api;
    /**
     * Release collection sorter/filter.
     *
     * @type CollectionManipulator
     * @since 0.1.0
     */
    private $collectionManipulator;

    /**
     * Initializer.
     *
     * @param GithubApiClient       $api                   Github API client
     *                                                     instance.
     * @param CollectionManipulator $collectionManipulator Release collection
     *                                                     filter/sorter.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @since 0.1.0
     */
    public function __construct(
        GithubApiClient $api,
        CollectionManipulator $collectionManipulator
    ) {
        $this->api = $api;
        $this->collectionManipulator = $collectionManipulator;
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
        $releases = $this->collectionManipulator->filterPrereleases($releases);
        if (!$releases) {
            return null;
        }
        $releases = $this->collectionManipulator->sortByDate(
            $releases,
            CollectionManipulator::SORT_ORDER_DESCENDING
        );
        return reset($releases);
    }

    /**
     * Retrieves release by tag, searches tag in release names/tags if no exact
     * match has been made.
     *
     * @param string $owner      Repository owner.
     * @param string $repository Repository name.
     * @param string $tag        Tag name.
     *
     * @return array|null Matched release or null if no match has been found.
     * @since 0.1.0
     */
    public function getSpecificRelease($owner, $repository, $tag)
    {
        $releases = $this->api->repos()->releases()->all($owner, $repository);
        $releases = $this->collectionManipulator->filterPrereleases($releases);
        $tag = mb_strtolower($tag, 'UTF-8');
        foreach ($releases as &$release) {
            $release['name'] = mb_strtolower($release['name'], 'UTF-8');
            $release['tag_name'] = mb_strtolower($release['tag_name'], 'UTF-8');
        }
        unset($release);
        foreach ($releases as $release) {
            if ($release['tag_name'] === $tag || $release['name'] === $tag) {
                return $release;
            }
        }
        foreach ($releases as $release) {
            if (mb_strpos($release['tag_name'], $tag, 0, 'UTF-8') !== false) {
                return $release;
            }
        }
        foreach ($releases as $release) {
            if (mb_strpos($release['name'], $tag, 0, 'UTF-8') !== false) {
                return $release;
            }
        }
        return null;
    }
}
