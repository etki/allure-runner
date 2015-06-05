<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\Exception\Configuration\UnknownParameterException;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;
use Symfony\Component\Yaml\Yaml;

/**
 * Creates configuration and populates with default values.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Builder
{
    /**
     * Configuration FQCN.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Configuration\Configuration';
    /**
     * List of uncommon setter names.
     *
     * @type string[]
     * @since 0.1.0
     */
    private static $uncommonSetterMap = array(
        Schema::PARAMETER_ENABLED => 'setIsEnabled',
        Schema::PARAMETER_SOURCES => 'addSources',
    );
    /**
     * Path to default configuration file.
     *
     * @type string
     * @since 0.1.0
     */
    private $configurationFilePath;
    /**
     * Filesystem helper instance.
     *
     * @type Filesystem
     * @since 0.1.0
     */
    private $filesystem;
    /**
     * Default configuration values.
     *
     * @type array
     * @since 0.1.0
     */
    private $defaultConfiguration;
    /**
     * I/O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param Filesystem            $filesystem   Filesystem helper.
     * @param PathResolver          $pathResolver Path resolver.
     * @param IOControllerInterface $ioController I/O controller.
     *
     * @since 0.1.0
     */
    public function __construct(
        Filesystem $filesystem,
        PathResolver $pathResolver,
        IOControllerInterface $ioController
    ) {
        $this->filesystem = $filesystem;
        $this->configurationFilePath = $pathResolver->getConfigurationFile(
            Configuration::DEFAULT_CONFIGURATION_FILE_NAME
        );
        $this->ioController = $ioController;
    }

    /**
     * Builds new configuration instance.
     *
     * @param array $values Configuration as array.
     *
     * @return Configuration
     * @since 0.1.0
     */
    public function build(array $values = array())
    {
        $configuration = new Configuration;
        $this->populate($configuration, $this->getDefaultConfiguration());
        $this->populate($configuration, $values);
        return $configuration;
    }

    /**
     * Populates configuration with values.
     *
     * @param Configuration $configuration Configuration instance.
     * @param array         $values        Values to populate configuration
     *                                     with.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function populate(Configuration $configuration, array $values)
    {
        foreach ($values as $key => $value) {
            if (!in_array($key, Schema::getParameterList(), true)) {
                $message = UnknownParameterException::getDefaultMessage($key);
                throw new UnknownParameterException($message);
            }
            $value = $this->resolveValue($key, $value);
            if (in_array($key, array_keys(self::$uncommonSetterMap), true)) {
                $setter = self::$uncommonSetterMap[$key];
            } else {
                $setter = 'set' . ucfirst($key);
            }
            call_user_func(array($configuration, $setter,), $value);
        }
        return $this;
    }

    /**
     * Retrieves default configuration.
     *
     * @return array
     * @since 0.1.0
     */
    private function getDefaultConfiguration()
    {
        if (!$this->defaultConfiguration) {
            $yaml = $this->filesystem->readFile($this->configurationFilePath);
            $this->defaultConfiguration = Yaml::parse($yaml);
        }
        return $this->defaultConfiguration;
    }

    /**
     * Resolves parameter value.
     *
     * @param string $key   Parameter key.
     * @param mixed  $value Parameter value.
     *
     * @return mixed
     * @since 0.1.0
     */
    private function resolveValue($key, $value)
    {
        if (is_string($value)
            && preg_match('~^%([\w_]+)%$~', $value, $matches)
        ) {
            $constantName = strtoupper($matches[1]);
            $value = $this->getConfigurationConstant($constantName, $value);
        }
        if ($value === Configuration::VALUE_AUTO) {
            switch ($key) {
                case Schema::PARAMETER_VERBOSITY:
                    return Configuration::DEFAULT_VERBOSITY_LEVEL;
                case Schema::PARAMETER_REPORT_VERSION:
                    return Configuration::DEFAULT_REPORT_VERSION;
                case Schema::PARAMETER_PREFERRED_ALLURE_VERSION:
                    return Configuration::DEFAULT_ALLURE_VERSION;
                case Schema::PARAMETER_OUTPUT_PREFIX_FORMAT:
                    return Configuration::DEFAULT_OUTPUT_PREFIX_FORMAT;
                case Schema::PARAMETER_TEMPORARY_DIRECTORY:
                    return $this->filesystem->getTemporaryDirectory();
                default:
                    return $this->getDefaultConfigurationValue($key);
            }
        }
        return $value;
    }

    /**
     * Returns configuration constant or value, specified as default.
     *
     * @param string $name         Constant name.
     * @param mixed  $defaultValue Value by default.
     *
     * @return mixed
     * @since 0.1.0
     */
    private function getConfigurationConstant($name, $defaultValue = null)
    {
        $fqcn = sprintf('%s::%s', self::CONFIGURATION_CLASS, $name);
        return defined($fqcn) ? constant($fqcn) : $defaultValue;
    }

    /**
     * Retrieves default configuration value by parameter name.
     *
     * @param string $parameter Parameter name.
     *
     * @return mixed|null
     * @since 0.1.0
     */
    private function getDefaultConfigurationValue($parameter)
    {
        $defaults = $this->getDefaultConfiguration();
        return isset($defaults[$parameter]) ? $defaults[$parameter] : null;
    }
}
