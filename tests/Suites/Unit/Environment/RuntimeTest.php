<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment;

use Etki\Testing\AllureFramework\Runner\Environment\Runtime;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests Runtime object.
 *
 * @method Runtime createTestInstance(PhpApi $phpApi)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Environment
 * @author  Etki <etki@etki.name>
 */
class RuntimeTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\Runtime';
    /**
     * PHP API FQCN.
     *
     * @since 0.1.0
     */
    const PHP_API_CLASS = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi';
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
     * Creates PHP API class mock.
     *
     * @param string $osFamily OS family to return on `uname()` call.
     *
     * @return PhpApi|Mock
     * @since 0.1.0
     */
    private function createPhpApiMock($osFamily)
    {
        $mock = $this
            ->getMockFactory(self::PHP_API_CLASS)
            ->getMock();
        $mock
            ->expects($this->any())
            ->method('uname')
            ->willReturn($osFamily);
        return $mock;
    }

    /**
     * Provides sample input and expected response.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function osFamilyProvider()
    {
        return array(
            array('Darwin', Runtime::FAMILY_MAC,),
            array('SunOS', Runtime::FAMILY_UNIX,),
            array('Linux', Runtime::FAMILY_LINUX,),
            array('Windows NT', Runtime::FAMILY_WINDOWS,),
        );
    }

    // tests

    /**
     * Verifies that OS family detecting goes as expected.
     *
     * @param string $input          Input to be returned by PHP API class mock
     *                               internally.
     * @param string $expectedResult Expected output.
     *
     * @dataProvider osFamilyProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testOsFamilyDetecting($input, $expectedResult)
    {
        $mock = $this->createPhpApiMock($input);
        $instance = $this->createTestInstance($mock);
        $this->assertSame($expectedResult, $instance->getOsFamily());
    }
}
