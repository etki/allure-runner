<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use Codeception\TestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use Symfony\Component\Process\Process;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class returns prepared process mocks.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class SymfonyProcessMockFactory
{
    /**
     * Mocked class FQCN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS = '\Symfony\Component\Process\Process';

    /**
     * Returns process mock.
     *
     * @param TestCase $testCase Test case currently being run.
     * @param int      $exitCode Process exit code.
     * @param string   $output   Process output.
     *
     * @return Mock|Process
     * @since 0.1.0
     */
    public function getMock(
        TestCase $testCase,
        $exitCode = null,
        $output = null
    ) {
        $mockBuilder = new MockBuilder($testCase, self::MOCKED_CLASS);
        $mockBuilder->setMethods(array('run', 'getExitCode', 'getOutput'));
        $mockBuilder->disableOriginalConstructor();
        $mock = $mockBuilder->getMock();
        if ($exitCode !== null) {
            $mock
                ->expects($testCase->any())
                ->method('run')
                ->willReturn($exitCode);
            $mock
                ->expects($testCase->any())
                ->method('getExitCode')
                ->willReturn($exitCode);
        }
        if ($output !== null) {
            $mock
                ->expects($testCase->any())
                ->method('getOutput')
                ->willReturn($output);
        }
        return $mock;
    }
}
