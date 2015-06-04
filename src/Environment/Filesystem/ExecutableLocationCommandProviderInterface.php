<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

/**
 * What have i done to write such long names.
 *
 * ---
 *
 * Realizations of this interface will return list of command templates that may
 * be used to find particularly named executable.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
interface ExecutableLocationCommandProviderInterface
{
    /**
     * Returns list of sprintf-ready executable location command templates.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getExecutableLocationCommandTemplates();
}
