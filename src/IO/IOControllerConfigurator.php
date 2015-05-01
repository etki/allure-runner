<?php

namespace Etki\Testing\AllureFramework\Runner\IO;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;

/**
 * Configures I\O controller.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
class IOControllerConfigurator
{
    /**
     * Builds I\O controller.
     *
     * @param IOControllerInterface $controller    Controller to configure.
     * @param Configuration         $configuration Configuration to use.
     *
     * @return IOControllerInterface
     * @since 0.1.0
     */
    public function configure(
        IOControllerInterface $controller,
        Configuration $configuration
    ) {
        if ($verbosity = $configuration->getVerbosity()) {
            $controller->setVerbosity($verbosity);
        }
        if ($controller instanceof PrefixAwareIOControllerInterface
            && $configuration->getOutputPrefixFormat()
        ) {
            $prefixFormat = $configuration->getOutputPrefixFormat();
            $controller->setPrefixFormat($prefixFormat);
        }
        return $controller;
    }
}
