<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Configuration;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests verbosity class compare functionality.
 *
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("expectedException")
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Configuration
 * @author  Etki <etki@etki.name>
 */ 
class VerbosityTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Configuration\Verbosity';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    /**
     * Returns class instance. `Verbosity` class *may* be instantiated :P
     *
     * @return Verbosity
     * @since 0.1.0
     */
    private function createInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }

    /**
     * Verifies that comparison is done right.
     *
     * @return void
     * @since 0.1.0
     */
    public function testComparison()
    {
        $instance = $this->createInstance();
        $this->assertLessThan(
            0,
            $instance->compareLevels(
                Verbosity::LEVEL_DEBUG,
                Verbosity::LEVEL_INFO
            )
        );
        $this->assertEquals(
            0,
            $instance->compareLevels(
                Verbosity::LEVEL_INFO,
                Verbosity::LEVEL_INFO
            )
        );
        $this->assertGreaterThan(
            0,
            $instance->compareLevels(
                Verbosity::LEVEL_ERROR,
                Verbosity::LEVEL_INFO
            )
        );
        $sortable = array(
            Verbosity::LEVEL_ERROR,
            Verbosity::LEVEL_NOTICE,
            Verbosity::LEVEL_DEBUG,
            Verbosity::LEVEL_INFO,
            Verbosity::LEVEL_MUTE,
            Verbosity::LEVEL_WARNING,
        );
        $expectedResult = array(
            Verbosity::LEVEL_DEBUG,
            Verbosity::LEVEL_NOTICE,
            Verbosity::LEVEL_INFO,
            Verbosity::LEVEL_WARNING,
            Verbosity::LEVEL_ERROR,
            Verbosity::LEVEL_MUTE,
        );
        $subject = $sortable;
        usort($subject, array($instance, 'compareLevels',));
        $this->assertEquals(
            $expectedResult,
            $subject
        );
    }

    /**
     * Verifies that exception is thrown on invalid input.
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\BadMethodCallException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidInput()
    {
        Verbosity::getLevelWeight('dummy');
    }
}
