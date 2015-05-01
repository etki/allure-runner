<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Generator;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\FullyQualifiedClassName;

/**
 * FQCN generator.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Generator
 * @author  Etki <etki@etki.name>
 */
class RandomFullyQualifiedClassNameGenerator
{
    /**
     * Latin alphabet.
     *
     * @since 0.1.0
     */
    const ALPHABET = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * Generates FQCN.
     *
     * @param int $depth       How many levels of nesting FQCN should have.
     * @param int $chunkLength Length of single namespace / class name.
     *
     * @return FullyQualifiedClassName Generated FQCN.
     * @since 0.1.0
     */
    public function generate($depth, $chunkLength = 4)
    {
        $templates = array_fill(0, $depth, $chunkLength);
        $chunks = array_map(array($this, 'generateRandomString'), $templates);
        return new FullyQualifiedClassName(implode('\\', $chunks));
    }

    /**
     * Generates random string using lowercase latin alphabet.
     *
     * @param int $length String length.
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @return string Generated string.
     * @since 0.1.0
     */
    private function generateRandomString($length)
    {
        $bytes = array();
        $alphabet = self::ALPHABET; // phpstorm mistreated direct [] access
        for ($i = 0; $i < $length; $i++) {
            $bytes[] = $alphabet[rand(0, strlen(self::ALPHABET) - 1)];
        }
        return implode('', $bytes);
    }
}
