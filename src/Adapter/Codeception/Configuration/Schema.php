<?php

namespace Etki\Testing\AllureFramework\Runner\Adapter\Codeception\Configuration;

/**
 * Codeception configuration schema.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Adapter\Codeception\Configuration
 * @author  Etki <etki@etki.name>
 */
final class Schema
{
    /**
     * Sources parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_SOURCES = 'sources';
    /**
     * Output directory parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_OUTPUT_DIRECTORY = 'outputDirectory';
    /**
     * Report version parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_REPORT_VERSION = 'reportVersion';
    /**
     * Executable parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_EXECUTABLE = 'executable';
    /**
     * Jar parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_JAR = 'jar';
    /**
     * Verbosity parameter name.
     *
     * @since 0.1.0
     */
    const PARAMETER_NAME_VERBOSITY = 'verbosity';
    /**
     * I/O controller's output prefix format parameter name. 
     * 
     * @since 0.1.0
     */
    const PARAMETER_NAME_OUTPUT_PREFIX_FORMAT = 'outputPrefixFormat';
    /**
     * Parameter value used as flag for automatic value selection.
     *
     * @since 0.1.0
     */
    const PARAMETER_VALUE_AUTO = 'auto';
    /**
     * List of default values.
     *
     * @type array
     * @since 0.1.0
     */
    private static $defaults = array(
        self::PARAMETER_NAME_SOURCES => array(),
        self::PARAMETER_NAME_OUTPUT_DIRECTORY => 'allure-report',
        self::PARAMETER_NAME_REPORT_VERSION => self::PARAMETER_VALUE_AUTO,
        self::PARAMETER_NAME_EXECUTABLE => null,
        self::PARAMETER_NAME_JAR => null,
        self::PARAMETER_NAME_VERBOSITY => self::PARAMETER_VALUE_AUTO,
        self::PARAMETER_NAME_OUTPUT_PREFIX_FORMAT => self::PARAMETER_VALUE_AUTO,
    );
    /**
     * Allowed parameter types in [parameterName => [types]] format.
     *
     * @type string[][]
     * @since 0.1.0
     */
    private static $allowedTypes = array(
        self::PARAMETER_NAME_SOURCES => array('array', 'string',),
        self::PARAMETER_NAME_OUTPUT_DIRECTORY => array('string',),
        self::PARAMETER_NAME_REPORT_VERSION => array('null', 'string',),
        self::PARAMETER_NAME_EXECUTABLE => array('null', 'string',),
        self::PARAMETER_NAME_JAR => array('null', 'string',),
        self::PARAMETER_NAME_VERBOSITY => array('null', 'string',),
        self::PARAMETER_NAME_OUTPUT_PREFIX_FORMAT => array('null', 'string',),
    );

    /**
     * Private constructor to prevent class instantiation. It's not that
     * something bad may happen, just for consistency.
     *
     * @since 0.1.0
     */
    private function __construct()
    {
    }

    /**
     * Returns default value for parameter.
     *
     * @param string $parameterName Name of the parameter.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     * @since 0.1.0
     */
    public static function getDefaultValue($parameterName)
    {
        return static::$defaults[$parameterName];
    }

    /**
     * Returns list of parameter names.
     *
     * @return string[]
     * @since 0.1.0
     */
    public static function getParameterNames()
    {
        return array_keys(static::$defaults);
    }

    /**
     * Validates parameters and returns boolean value indicating validation
     * success.
     *
     * @param string $parameterName  Parameter name.
     * @param mixed  $parameterValue Parameter value.
     *
     * @return bool
     * @since 0.1.0
     */
    public static function validateParameter(
        $parameterName,
        $parameterValue
    ) {
        $allowedTypes = static::$allowedTypes[$parameterName];
        foreach ($allowedTypes as $type) {
            switch ($type) {
                case 'string':
                    if (is_string($parameterValue)) {
                        return true;
                    }
                    break;
                case 'bool':
                    //
                case 'boolean':
                    if (is_bool($parameterValue)) {
                        return true;
                    }
                    break;
                case 'array':
                    if (is_array($parameterValue)) {
                        return true;
                    }
                    break;
                case 'null':
                    if ($parameterValue === null) {
                        return true;
                    }
            }
        }
        return false;
    }
}
