<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Test;

use ReflectionClass;

/**
 * Basic test that is capable of test instance creation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Test
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractClassAwareTest extends AbstractTest
{
    /**
     * Returns tested class name.
     *
     * @return string
     * @since 0.1.0
     */
    abstract public function getTestedClass();

    /**
     * Creates test instance.
     *
     * @return object
     * @since 0.1.0
     */
    protected function createTestInstance()
    {
        $reflection = new ReflectionClass($this->getTestedClass());
        return $reflection->newInstanceArgs(func_get_args());
    }
}
