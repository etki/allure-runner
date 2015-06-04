<?php

namespace Etki\Testing\AllureFramework\Runner\Api\Github;

use DateTime;

/**
 * This class manipulates release collections.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseCollectionManipulator
{
    /**
     * Constant for ascending sort order.
     *
     * @since 0.1.0
     */
    const SORT_ORDER_ASCENDING = 'asc';
    /**
     * Constant for descending sort order.
     *
     * @since 0.1.0
     */
    const SORT_ORDER_DESCENDING = 'desc';
    
    /**
     * Returns release collection without prereleases.
     *
     * @param array $releases List of releases.
     *
     * @return array List of filtered releases.
     * @since 0.1.0
     */
    public function filterPrereleases(array $releases)
    {
        $filtered = array();
        // yes, i simply don't like callback-driven design
        foreach ($releases as &$release) {
            if (!$release['prerelease']) {
                $filtered[] = $release;
            }
        }
        return $filtered;
    }

    /**
     * Sorts releases by date.
     *
     * @param array  $releases List of releases.
     * @param string $order    Sort order.
     *
     * @return array Sorted releases.
     * @since 0.1.0
     */
    public function sortByDate(
        array $releases,
        $order = self::SORT_ORDER_DESCENDING
    ) {
        usort($releases, array($this, 'dateSortCallback'));
        if ($order === self::SORT_ORDER_ASCENDING) {
            $releases = array_reverse($releases);
        }
        return $releases;
    }

    /**
     * Compares two releases by publish date.
     *
     * @param array $releaseA First release to compare.
     * @param array $releaseB Second release to compare.
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @return int `usort()`-compatible comparison result.
     * @since 0.1.0
     */
    private function dateSortCallback(array $releaseA, array $releaseB)
    {
        $releaseADate = new DateTime($releaseA['published_at']);
        $releaseBDate = new DateTime($releaseB['published_at']);
        return $releaseADate->getTimestamp() - $releaseBDate->getTimestamp();
    }
}
