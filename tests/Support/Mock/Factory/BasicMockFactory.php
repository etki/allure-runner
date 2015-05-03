<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use Codeception\TestCase\Test;

/**
 * Generic mock factory that will be applicable to most cases.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class BasicMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class name.
     *
     * @type string
     * @since 0.1.0
     */
    private $class;

    /**
     * Initializer.
     *
     * @param string $class Full class name.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Returns mocked class name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return $this->class;
    }
}
