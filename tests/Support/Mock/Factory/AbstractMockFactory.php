<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use BadMethodCallException;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractTest;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use ReflectionClass;
use ReflectionMethod;

/**
 * Base mock factory.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractMockFactory
{
    /**
     * Mocked class reflection.
     *
     * @type ReflectionClass
     * @since 0.1.0
     */
    private $mockedClassReflection;
    /**
     * List of methods of mocked class.
     *
     * @type ReflectionMethod[]
     * @since 0.1.0
     */
    private $mockedClassMethods;
    /**
     * Current test.
     *
     * @type AbstractTest
     * @since 0.1.0
     */
    private $test;
    
    /**
     * Returns class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    abstract public function getMockedClass();

    /**
     * Creates mock builder.
     *
     * @param string[] $methods List of methods to override.
     *
     * @return MockBuilder
     * @since 0.1.0
     */
    protected function getMockBuilder(array $methods = null)
    {
        $builder = $this->getTest()->getMockBuilder($this->getMockedClass());
        if ($methods) {
            $builder->setMethods($methods);
        }
        return $builder;
    }

    /**
     * Retrieves mocked class reflection.
     *
     * @return ReflectionClass
     * @since 0.1.0
     */
    protected function getMockedClassReflection()
    {
        if (!isset($this->mockedClassReflection)) {
            $this->mockedClassReflection
                = new ReflectionClass($this->getMockedClass());
        }
        return $this->mockedClassReflection;
    }

    /**
     * Retrieves methods of class being mocked.
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getMockedClassMethods()
    {
        if (!isset($this->mockedClassMethods)) {
            $this->mockedClassMethods
                = $this->getMockedClassReflection()->getMethods();
        }
        return $this->mockedClassMethods;
    }

    /**
     * Filters current class methods.
     *
     * @param int $filter Filter mask
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getFilteredMockedClassMethods($filter)
    {
        $methods = array();
        foreach ($this->getMockedClassMethods() as $method) {
            if ($method->getModifiers() & $filter) {
                $methods[] = $method;
            }
        }
        return $methods;
    }

    /**
     * Retrieves list of public methods.
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getMockedClassPublicMethods()
    {
        $filter = ReflectionMethod::IS_PUBLIC;
        return $this->getFilteredMockedClassMethods($filter);
    }

    /**
     * Creates and returns prepared mock builder.
     *
     * @param array $arguments Constructor arguments.
     *
     * @return MockBuilder
     * @since 0.1.0
     */
    protected function getPreparedMockBuilder(array $arguments = array())
    {
        $reflection = new ReflectionClass($this->getMockedClass());
        $filter = ReflectionMethod::IS_PUBLIC & ~ReflectionMethod::IS_STATIC;
        $methods = $reflection->getMethods($filter);
        $methodNames = array();
        foreach ($methods as $method) {
            $methodNames[] = $method->name;
        }
        $builder = $this->getMockBuilder($methodNames);
        if ($arguments) {
            $builder->setConstructorArgs($arguments);
        }
        return $builder;
    }

    /**
     * Retrieves mock.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getMock()
    {
        return $this->getPreparedMockBuilder(func_get_args())->getMock();
    }

    /**
     * Returns mock that doesn't call constructor.
     *
     * @param AbstractTest $test Currently run test.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getConstructorlessMock()
    {
        $builder = $this->getPreparedMockBuilder();
        $builder->disableOriginalConstructor();
        return $builder->getMock();
    }

    /**
     * Gets dummy mock that simply returns null wherever it wants.
     *
     * @param array $arguments List of constructor arguments.
     *
     * @deprecated
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getDummyMockDeprecated(array $arguments = array())
    {
        $mock = $this->getMock($arguments);
        foreach ($this->getMockedClassPublicMethods() as $method) {
            $mock
                ->expects($this->getTest()->any())
                ->method($method->name)
                ->withAnyParameters()
                ->willReturn(null);
        }
        return $mock;
    }

    /**
     * Gets dummy mock that simply returns null wherever it wants.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getDummyMock()
    {
        $mock = $this->getConstructorlessMock();
        foreach ($this->getMockedClassPublicMethods() as $method) {
            $mock
                ->expects($this->getTest()->any())
                ->method($method->name)
                ->withAnyParameters()
                ->willReturn(null);
        }
        return $mock;
    }

    /**
     * Sets current test.
     *
     * @param AbstractTest $test Currently run test.
     *
     * @return $this Current instance;
     * @since 0.1.0
     */
    public function setTest(AbstractTest $test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * Retrieves current test.
     *
     * @return AbstractTest
     * @since 0.1.0
     */
    protected function getTest()
    {
        if (!$this->test) {
            throw new BadMethodCallException('Test instance hasn\'t been set');
        }
        return $this->test;
    }
}
