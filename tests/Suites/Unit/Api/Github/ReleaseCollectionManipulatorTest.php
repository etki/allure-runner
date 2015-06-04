<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseCollectionManipulator;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\Api\BaseApiResponseLoader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * Checks release collection manipulation testing.
 *
 * @method ReleaseCollectionManipulator createTestInstance()
 *
 * @IgnoreAnnotation("dataProvider")
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseCollectionManipulatorTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseCollectionManipulator';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility methods

    /**
     * Returns test subject FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }

    /**
     * Provides sample data for sorting tests.
     *
     * @return array
     * @since 0.1.0
     */
    public function sortDataProvider()
    {
        $data = array(
            array(array(), array(),), // welcome to the stupid camp
        );
        /** @type BaseApiResponseLoader $loader */
        $loader = $this->getResponseLoader('github');
        $responses = $loader->getResponses('releases.all', 'sorted-desc');
        foreach ($responses as $response) {
            $data[] = array(
                $response->getData(),
                $response->getData(),
            );
        }
        $responses = $loader->getResponses('releases.all', 'sorted-asc');
        foreach ($responses as $response) {
            $data[] = array(
                $response->getData(),
                array_reverse($response->getData()),
            );
        }
        return $data;
    }

    /**
     * Provides test samples for filtering test.
     *
     * @return array
     * @since 0.1.0
     */
    public function filteringDataProvider()
    {
        $data = array(
            array(array(), array(),),
        );
        $filterCallback = function (array $release) {
            return !$release['prerelease'];
        };
        $loader = $this->getResponseLoader('github');
        $responses = $loader->getResponses(
            'releases.all',
            'prereleases-included'
        );
        foreach ($responses as $response) {
            $data[] = array(
                $response->getData(),
                array_filter($response->getData(), $filterCallback),
            );
        }
        $responses = $loader->getResponses(
            'releases.all',
            'prereleases-excluded'
        );
        foreach ($responses as $response) {
            $data[] = array(
                $response->getData(),
                $response->getData(),
            );
        }
        return $data;
    }

    // tests

    /**
     * Tests sorting.
     *
     * @param array $releases        List of releases.
     * @param array $descendingOrder Resulting collection in descending order.
     *
     * @dataProvider sortDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testSorting(array $releases, array $descendingOrder)
    {
        $instance = $this->createTestInstance();
        $sortedCollection = $instance->sortByDate(
            $releases,
            ReleaseCollectionManipulator::SORT_ORDER_DESCENDING
        );
        $this->assertSame($descendingOrder, $sortedCollection);
        $sortedCollection = $instance->sortByDate(
            $releases,
            ReleaseCollectionManipulator::SORT_ORDER_ASCENDING
        );
        $this->assertSame(array_reverse($descendingOrder), $sortedCollection);
    }

    /**
     * Tests release filtering.
     *
     * @param array $unfiltered         Unfiltered collection.
     * @param array $expectedCollection Collection expected on exit.
     *
     * @dataProvider filteringDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testFiltering(array $unfiltered, array $expectedCollection)
    {
        $instance = $this->createTestInstance();
        $filtered = $instance->filterPrereleases($unfiltered);
        sort($filtered);
        sort($expectedCollection);
        $this->assertSame($expectedCollection, $filtered);
    }
}
