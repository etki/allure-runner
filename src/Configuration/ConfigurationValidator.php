<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
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
class ConfigurationValidator
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
     * @param ValidatorInterface    $validator    Validator to inject.
     * @param IOControllerInterface $ioController I/O controller.
     *
     * @since 0.1.0
     */
    public function __construct(
        ValidatorInterface $validator,
        IOControllerInterface $ioController
    ) {
        $this->validator = $validator;
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
