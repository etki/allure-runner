<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\Helper;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * This class simply dumps configuration.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Helper
 * @author  Etki <etki@etki.name>
 */
class ConfigurationDumper
{
    /**
     * Dumps runner configuration.
     *
     * @param Configuration         $configuration
     * @param IOControllerInterface $ioController
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function dump(
        Configuration $configuration,
        IOControllerInterface $ioController
    ) {
        $ioController->writeLine('Configuration dump:', Verbosity::LEVEL_DEBUG);
        
        $values = array(
            'Verbosity' => $configuration->getVerbosity(),
            'Data sources' => $configuration->getSources(),
            'Output directory' => $configuration->getReportPath(),
            'Executable location' => $configuration->getExecutable(),
            '`.jar` file location' => $configuration->getJar(),
            'Report version' => $configuration->getReportVersion(),
            'Output prefix format' => $configuration->getOutputPrefixFormat(),
        );

        foreach ($values as $name => $value) {
            $message = sprintf(
                '  %s %s',
                str_pad($name . ':', 22, ' ', STR_PAD_RIGHT),
                $this->convertValue($value)
            );
            $ioController->writeLine($message, Verbosity::LEVEL_DEBUG);
        }
    }

    /**
     * Converts value for output.
     *
     * @param mixed $value Value that (probably) needs formatting)
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    private function convertValue($value)
    {
        if ($value === null) {
            return 'null';
        } elseif (is_array($value)) {
            return '[' . implode(', ', $value) . ']';
        }
        return $value;
    }
}
