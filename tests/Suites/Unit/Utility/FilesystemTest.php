<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Codeception\TestCase\Test;
use UnitTester;

/**
 * Tests filesystem utility class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Utility
 * @author  Etki <etki@etki.name>
 */
class FilesystemTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    // utility

    /**
     * Creates test instance.
     *
     * @return Filesystem
     * @since 0.1.0
     */
    private function createTestInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }
    
    /**
     * Provides paths for normalization.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function pathNormalizationProvider()
    {
        return array(
            array(
                '../tests/./data/../Suites/Unit/',
                null,
                '../tests/Suites/Unit/',
            ),
            array('bin/../local/bin/allure', '/usr', '/usr/local/bin/allure',),
        );
    }

    // tests

    /**
     * Tests `normalizePath()` method.
     *
     * @param string $rawPath          Path to normalize.
     * @param string $currentDirectory Fake current working directory.
     * @param string $expectedResult   Expected result.
     *
     * @dataProvider pathNormalizationProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testPathNormalization(
        $rawPath,
        $currentDirectory,
        $expectedResult
    ) {
        $filesystem = $this->createTestInstance();
        $this->assertSame(
            $expectedResult,
            $filesystem->normalizePath($rawPath, $currentDirectory)
        );
    }
}
