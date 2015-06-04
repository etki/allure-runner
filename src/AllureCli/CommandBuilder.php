<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

use Etki\Testing\AllureFramework\Runner\Exception\AllureCli\ExecutableNotSpecifiedException;

/**
 * This class builds command to invoke Allure.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class CommandBuilder
{
    /**
     * Path to executable.
     *
     * @type string
     * @since 0.1.0
     */
    private $executable;
    /**
     * Allure command to run (`generate`, innit?).
     *
     * @type string
     * @since 0.1.0
     */
    private $command;
    /**
     * List of options in [name => [value]] format.
     *
     * @type string[][]
     * @since 0.1.0
     */
    private $options = array();
    /**
     * List of argument values.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $arguments = array();
    /**
     * List of arguments that are placed after `--` separator.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $postArguments = array();

    /**
     * Initializer.
     *
     * @param string $executable Path ro executable (may be compound, i.e.
     *                           consist of several space-delimited parts).
     * @param string $command    Command to run.
     *
     * @since 0.1.0
     */
    public function __construct($executable = null, $command = null)
    {
        $this->setExecutable($executable);
        $this->setCommand($command);
    }

    /**
     * Sets executable to run.
     *
     * @param string $executable Executable to set.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;
        return $this;
    }

    /**
     * Sets command to run.
     *
     * @param string $command Command to run.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }

    /**
     * Adds single option value.
     *
     * @param string $name  Option name.
     * @param string $value Option value.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function addOption($name, $value)
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = array();
        }
        $this->options[$name][] = $value;
        return $this;
    }

    /**
     * Adds several values for single option at once.
     *
     * @param string   $name   Option name.
     * @param string[] $values Option values.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function addOptionValues($name, array $values)
    {
        foreach ($values as $value) {
            $this->addOption($name, $value);
        }
        return $this;
    }

    /**
     * Adds several options at once.
     *
     * @param string[]|string[][] $options Options in [name => value] or
     *                                     [name => [values]] format.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function addOptions(array $options)
    {
        foreach ($options as $name => $value) {
            if (is_array($value)) {
                $this->addOptionValues($name, $value);
            } else {
                $this->addOption($name, $value);
            }
        }
        return $this;
    }

    /**
     * Adds single argument
     *
     * @param string $value Argument value.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function addArgument($value)
    {
        $this->arguments[] = $value;
        return $this;
    }

    /**
     * Adds several arguments at once.
     *
     * @param string[] $values List of arguments.
     *
     * @return $this Current instance,
     * @since 0.1.0
     */
    public function addArguments(array $values)
    {
        foreach ($values as $value) {
            $this->addArgument($value);
        }
        return $this;
    }

    /**
     * Adds post-argument (argument which comes after `--` separator).
     *
     * @param string $value Argument value.
     *
     * @return $this Current instance,
     * @since 0.1.0
     */
    public function addPostArgument($value)
    {
        $this->postArguments[] = $value;
        return $this;
    }

    /**
     * Adds list of post-arguments that go after `--` delimiter.
     *
     * @param string[] $values List of arguments.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function addPostArguments(array $values)
    {
        foreach ($values as $value) {
            $this->addPostArgument($value);
        }
        return $this;
    }

    /**
     * Builds command.
     *
     * @return string Built command.
     * @since 0,1,0
     */
    public function getCommand()
    {
        if (!$this->executable) {
            $message = ExecutableNotSpecifiedException::getDefaultMessage();
            throw new ExecutableNotSpecifiedException($message);
        }
        $queue = array($this->executable,);
        if ($this->command) {
            $queue[] = $this->command;
        }
        $optionFormat = '--%s %s';
        foreach ($this->options as $name => $values) {
            foreach ($values as $value) {
                $value = $this->sanitizeValue($value);
                $queue[] = sprintf($optionFormat, ltrim($name, '-'), $value);
            }
        }
        foreach ($this->arguments as $argument) {
            $queue[] = $this->sanitizeValue($argument);
        }
        $queue += $this->arguments;
        if ($this->postArguments) {
            $queue[] = '--';
            foreach ($this->postArguments as $postArgument) {
                $queue[] = $this->sanitizeValue($postArgument);
            }
        }
        return implode(' ', $queue);
    }

    /**
     * Sanitizes argument value.
     *
     * @param string $value Value to sanitize.
     *
     * @return string
     * @since 0.1.0
     */
    public function sanitizeValue($value)
    {
        if (preg_match('~\s~u', $value)) {
            $value = sprintf('"%s"', $value);
        }
        return $value;
    }
}
