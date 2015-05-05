<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandsProviderInterface;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandsProviderInterface;

/**
 * Provides command templates for Windows OS.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider
 * @author  Etki <etki@etki.name>
 */
class WindowsCommandTemplateProvider implements
    ExecutableLocationCommandsProviderInterface,
    FileLocationCommandsProviderInterface
{
    /**
     * Template for executable search command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_WHERE = 'where %s';

    /**
     * {@inheritdoc}
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getExecutableLocationCommandTemplates()
    {
        return array(self::COMMAND_TEMPLATE_WHERE);
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplates()
    {
        return array();
    }
}
