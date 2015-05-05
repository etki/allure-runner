<?php
// for the glory of satan
namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Vendor\Symfony\Component\Process;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Symfony\Component\Process\Process;
use Codeception\TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class returns prepared process mocks.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class ProcessMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class FQCN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS = '\Symfony\Component\Process\Process';

    /**
     * Returns mocked class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return self::MOCKED_CLASS;
    }
    
    /**
     * Returns process mock.
     *
     * @param int      $exitCode Process exit code.
     * @param string   $output   Process output.
     *
     * @return Mock|Process
     * @since 0.1.0
     */
    public function getPreparedMock(
        $exitCode = null,
        $output = null
    ) {
        $mockBuilder = $this->getPreparedMockBuilder();
        $mockBuilder->disableOriginalConstructor();
        $mockBuilder->setMethods(array('run', 'getExitCode', 'getOutput'));
        $mock = $mockBuilder->getMock();
        if ($exitCode !== null) {
            $mock
                ->expects($this->getTest()->any())
                ->method('run')
                ->willReturn($exitCode);
            $mock
                ->expects($this->getTest()->any())
                ->method('getExitCode')
                ->willReturn($exitCode);
        }
        if ($output !== null) {
            $mock
                ->expects($this->getTest()->any())
                ->method('getOutput')
                ->willReturn($output);
        }
        return $mock;
    }
}
