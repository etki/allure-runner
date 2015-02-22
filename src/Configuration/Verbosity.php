<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\Exception\BadMethodCallException;

/**
 * This class specifies various verbosity levels and compares them.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Verbosity
{
    /**
     * Constant for complete mute.
     *
     * @since 0.1.0
     */
    const LEVEL_MUTE = 'mute';
    /**
     * Constant for showing only error messages.
     *
     * @since 0.1.0
     */
    const LEVEL_ERROR = 'error';
    /**
     * Constant for showing all warning and higher priority messages.
     *
     * @since 0.1.0
     */
    const LEVEL_WARNING = 'warning';
    /**
     * Constant for showing all messages with priority higher or equal than
     * 'informational'.
     *
     * @since 0.1.0
     */
    const LEVEL_INFO = 'info';
    /**
     * Constant for showing all messages except for debug ones.
     *
     * @since 0.1.0
     */
    const LEVEL_NOTICE = 'notice';
    /**
     * Constant for showing every possible message.
     *
     * @since 0.1.0
     */
    const LEVEL_DEBUG = 'debug';
    /**
     * Map of level weights.
     *
     * @type int[]
     * @since 0.1.0
     */
    private static $levelWeights = array(
        self::LEVEL_DEBUG => 1,
        self::LEVEL_NOTICE => 2,
        self::LEVEL_INFO => 3,
        self::LEVEL_WARNING => 4,
        self::LEVEL_ERROR => 5,
        self::LEVEL_MUTE => 99,
    );

    /**
     * `usort()`-compatible level compare function. Returns integer lesser than,
     * equal or more than 0 in case `$levelA` is smaller, equal or bigger than
     * `$levelB` correspondingly.
     *
     * @param string $levelA First verbosity level.
     * @param string $levelB Second verbosity level.
     *
     * @return int Comparison result.
     * @since 0.1.0
     */
    public static function compareLevels($levelA, $levelB)
    {
        $weightA = static::getLevelWeight($levelA);
        $weightB = static::getLevelWeight($levelB);
        return $weightA - $weightB;
    }

    /**
     * Returns verbosity level weight.
     *
     * @param string $level Level name.
     *
     * @return int
     * @since 0.1.0
     */
    public static function getLevelWeight($level)
    {
        $normalizedLevel = strtolower($level);
        if (!isset(static::$levelWeights[$normalizedLevel])) {
            $message = sprintf('Unknown verbosity level `%s`', $level);
            throw new BadMethodCallException($message);
        }
        return static::$levelWeights[$normalizedLevel];
    }
}
