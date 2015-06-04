<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Simple annotation reader override to include custom annotations.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class AnnotationReaderFactory
{
    /**
     * List of ignored annotations.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $ignoredAnnotations = array();

    /**
     * A little-bit flawed initializer that ensures that ignored annotations are
     * ignored.
     *
     * @param string[] $ignoredAnnotations List of annotations to be ignored by
     *                                     produced readers.
     *
     * @codeCoverageIgnore
     *
     * @since 0.1.0
     */
    public function __construct(array $ignoredAnnotations)
    {
        $this->ignoredAnnotations = $ignoredAnnotations;
    }

    /**
     * Creates new reader.
     *
     * @codeCoverageIgnore
     *
     * @return AnnotationReader
     * @since 0.1.0
     */
    public function getReader()
    {
        $reader = new AnnotationReader;
        foreach ($this->ignoredAnnotations as $annotation) {
            $reader->addGlobalIgnoredName($annotation);
        }
        return $reader;
    }
}
