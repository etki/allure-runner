<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;

/**
 * Tests Allure executable resolver under different conditions,
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class AllureExecutableResolverTest extends AbstractClassAwareTest
{
    /**
     * Tester instance.
     *
     * @type FunctionalTester
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
        return Registry::ALLURE_EXECUTABLE_RESOLVER_CLASS;
    }

    // tests
    
    public function testMe()
    {
        // todo
    }
}
