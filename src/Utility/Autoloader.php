<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use BadMethodCallException;

/**
 * Simple PSR-4 compatible autoloader.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class Autoloader
{
    /**
     * List of registered namespaces.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $namespaces = array();

    /**
     * Registers namespace for autoloading.
     *
     * @param string $namespace Namespace.
     * @param string $root      Namespace root directory.
     *
     * @return void
     * @since 0.1.0
     */
    public function registerNamespace($namespace, $root)
    {
        $root = rtrim($root, '\\/');
        $namespace = trim($namespace, '\\');
        if (!is_dir($root)) {
            $message = sprintf(
                'Couldn\'t add `%s` as namespace root: it is not a directory',
                $root
            );
            throw new BadMethodCallException($message);
        }
        $this->namespaces[$namespace] = $root;
    }

    /**
     * Loads class.
     *
     * @param string $className FQCN.
     *
     * @return void
     * @since 0.1.0
     */
    public function loadClass($className)
    {
        $className = trim($className, '\\');
        $namespaces = $this->getAppropriateNamespaces($className);
        if (!$namespaces) {
            return;
        }
        foreach ($namespaces as $namespace) {
            if ($this->tryLoadClass($namespace, $className)) {
                return;
            }
        }
    }

    /**
     * Returns list of namespaces that conform current class.
     *
     * @param string $className FQCN.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return string[]
     * @since 0.1.0
     */
    private function getAppropriateNamespaces($className)
    {
        $segments = explode('\\', $className);
        $segments = array_slice($segments, 0, sizeof($segments) - 1);
        $classNamespace = implode('\\', $segments);
        if ($classNamespace === '') {
            if (isset($this->namespaces[''])) {
                return array('',);
            }
            return array();
        }
        $appropriateNamespaces = array();
        foreach (array_keys($this->namespaces) as $namespace) {
            if (strpos($classNamespace, $namespace) === 0) {
                $appropriateNamespaces[] = $namespace;
            }
        }
        return $appropriateNamespaces;
    }

    /**
     * Tries to load class from namespace.
     *
     * @param string $namespace Namespace class is contained in, directly or in
     *                          child namespace.
     * @param string $className FQCN.
     *
     * @return bool True if load has been successful, false otherwise.
     * @since 0.1.0
     */
    private function tryLoadClass($namespace, $className)
    {
        $root = $this->namespaces[$namespace];
        $relativeClassName = substr($className, strlen($namespace) + 1);
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName);
        $path = $root . DIRECTORY_SEPARATOR . $classPath . '.php';
        if (file_exists($path) && is_readable($path)) {
            include_once $path;
            if (class_exists('\\' . $className, false)) {
                return true;
            }
        }
        return false;
    }
}
