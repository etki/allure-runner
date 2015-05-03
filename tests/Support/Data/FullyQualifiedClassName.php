<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data;

/**
 * FQCN processor.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data
 * @author  Etki <etki@etki.name>
 */
class FullyQualifiedClassName
{
    /**
     * List of class namespaces.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $namespaces = array();
    /**
     * Name of the class.
     *
     * @type string
     * @since 0.1.0
     */
    private $className;

    /**
     * Initializer.
     *
     * @param string $fqcn FQCN.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct($fqcn)
    {
        $chunks = array_filter(explode('\\', $fqcn));
        $this->className = array_pop($chunks);
        $this->namespaces = $chunks;
    }

    /**
     * Returns list of namespaces.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Returns class name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Returns root namespace (if present).
     *
     * @return string|null
     * @since 0.1.0
     */
    public function getRootNamespace()
    {
        return $this->namespaces ? $this->namespaces[0] : null;
    }

    /**
     * Returns fill namespace (if present).
     *
     * @return null|string
     * @since 0.1.0
     */
    public function getNamespace()
    {
        return $this->namespaces ? implode('\\', $this->namespaces) : null;
    }

    /**
     * Returns absolute namespace with leading backwards slash (if present).
     *
     * @return null|string
     * @since 0.1.0
     */
    public function getAbsoluteNamespace()
    {
        return $this->namespaces ? '\\' . $this->getNamespace() : null;
    }

    /**
     * Returns fully-qualified class name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getFqcn()
    {
        $prefix = $this->getNamespace() ? $this->getNamespace() . '\\' : '';
        return $prefix . $this->className;
    }

    /**
     * Returns fully-qualified class name with leading backwards slash.
     *
     * @return string
     * @since 0.1.0
     */
    public function getAbsoluteFqcn()
    {
        return '\\' . $this->getFqcn();
    }

    /**
     * To-string conversion method.
     *
     * @return string
     * @since 0.1.0
     */
    public function __toString()
    {
        return $this->getAbsoluteFqcn();
    }
}
