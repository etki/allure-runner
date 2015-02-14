<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

/**
 * This class specifies runner behavior on different scenarios.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * 
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Behavior
{
    private $throwOnMissingBinary = true;
    private $throwOnNonNullExitCode = true;
    private $useAutoloadingClassExistsCalls = true;
}
