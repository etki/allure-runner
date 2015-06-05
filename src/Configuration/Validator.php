<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Validation\ValidatorFactory;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validates configuration
 *
 * @codeCoverageIgnore
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Validator
{
    /**
     * Underlying validator.
     *
     * @type ValidatorInterface
     * @since 0.1.0
     */
    private $validator;
    /**
     * I/O controller instance.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param ValidatorFactory      $validatorFactory Validator factory.
     * @param IOControllerInterface $ioController     I/O controller.
     *
     * @since 0.1.0
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        IOControllerInterface $ioController
    ) {
        $this->validator = $validatorFactory->getValidator();
        $this->ioController = $ioController;
    }

    /**
     * Validates configuration.
     *
     * @param Configuration $configuration Configuration to validate.
     *
     * @return bool True if configuration is valid, false otherwise.
     * @since 0.1.0
     */
    public function validate(Configuration $configuration)
    {
        // todo move this somewhere
        AnnotationRegistry::registerLoader('class_exists');
        
        $violations = $this->validator->validate($configuration);
        if (!$violations->count()) {
            $message = 'Successfully validated provided configuration';
            $this->ioController->writeLine($message, Verbosity::LEVEL_DEBUG);
            return true;
        }
        /** @type ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $message = sprintf(
                '%s: %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
        }
        $message = 'Configuration has failed validation';
        $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
        return false;
    }
}
