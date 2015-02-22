<?php

namespace Etki\Testing\AllureFramework\Runner\Adapter\PHPUnit;

use PHPUnit_Framework_BaseTestListener as AbstractTestListener;
use PHPUnit_Framework_TestSuite as TestSuite;

/**
 * PHPUnit listener implementation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Adapter\PHPUnit
 * @author  Etki <etki@etki.name>
 */
class Listener extends AbstractTestListener
{
    /**
     * List of suites Allure Runner will run after. Implemented as the only
     * option to prevent running after every suite.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $suites = array();

    /**
     * One fat ugly initializer.
     *
     * @param string|string[] $sources
     * @param string          $outputDirectory
     * @param string|string[] $suites
     * @param string          $executableLocation
     * @param string          $jarLocation
     * @param string          $reportVersion
     * @param string          $verbosity
     * @param string          $outputPrefixFormat
     * 
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $sources,
        $outputDirectory,
        $suites = null,
        $executableLocation = null,
        $jarLocation = null,
        $reportVersion = null,
        $verbosity = null,
        $outputPrefixFormat = null
    ) {
        if ($suites) {
            if (!is_array($suites)) {
                $suites = array($suites,);
            }
            $this->suites = $suites;
        }
    }
    public function endTestSuite(TestSuite $suite)
    {
        
    }
}
