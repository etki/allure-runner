<?php

namespace Etki\Testing\AllureFramework\Runner\IO\Controller;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * Base controller functionality.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractIOController implements IOControllerInterface
{
    /**
     * Verbosity level.
     *
     * @type int
     * @since 0.1.0
     */
    private $verbosity;

    /**
     * Sets verbosity level.
     *
     * @param int $verbosity Verbosity level.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function setVerbosity($verbosity)
    {
        $this->verbosity = $verbosity;
    }

    /**
     * Retrieves verbosity.
     *
     * @codeCoverageIgnore
     *
     * @return int
     * @since 0.1.0
     */
    protected function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * Tells if provided verbosity level is allowed.
     *
     * @param int $verbosity Verbosity level.
     *
     * @return bool
     * @since 0.1.0
     */
    protected function isAllowedVerbosityLevel($verbosity)
    {
        return Verbosity::compareLevels($verbosity, $this->verbosity) >= 0;
    }
}
