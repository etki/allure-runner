<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandsProviderInterface;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandsProviderInterface;

/**
 * This class provides
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider
 * @author  Etki <etki@etki.name>
 */
class UnixCommandTemplateProvider implements
    ExecutableLocationCommandsProviderInterface,
    FileLocationCommandsProviderInterface
{
    /**
     * Template for locate file command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_LOCATE = 'locate %s';
    /**
     * Template for simple find command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_FIND = 'find / -name %s';
    /**
     * Template for which command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_WHICH = 'which %s';
    
    /**
     * Returns list of command templates to locate particular executable by
     * name.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getExecutableLocationCommandTemplates()
    {
        return array(self::COMMAND_TEMPLATE_WHICH);
    }

    /**
     * Returns list of command templates to locate particular file by name.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplates()
    {
        return array(self::COMMAND_TEMPLATE_LOCATE);
    }
}
