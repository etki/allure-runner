<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\Validation;

use Etki\Testing\AllureFramework\Runner\Utility\Reflection\AnnotationReaderFactory;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Another uber-simple factory whose purpose is only to decouple object
 * creation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility\Validation
 * @author  Etki <etki@etki.name>
 */
class ValidatorFactory
{
    /**
     * Annotation reader.
     *
     * @type AnnotationReaderFactory
     * @since 0.1.0
     */
    private $readerFactory;

    /**
     * Initializer.
     *
     * @param AnnotationReaderFactory $readerFactory Annotation reader factory
     *                                               (what a surprise).
     *
     * @codeCoverageIgnore
     *
     * @since 0.1.0
     */
    public function __construct(
        AnnotationReaderFactory $readerFactory
    ) {
        $this->readerFactory = $readerFactory;
    }

    /**
     * Returns new validator.
     *
     * @codeCoverageIgnore
     *
     * @return ValidatorInterface
     * @since 0.1.0
     */
    public function getValidator()
    {
        $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping($this->readerFactory->getReader())
                ->getValidator();
        return $validator;
    }
}
