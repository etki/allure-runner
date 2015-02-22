<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

use Etki\Testing\AllureFramework\Runner\Exception\Environment\UnknownOsFamilyException;
use Symfony\Component\Process\Process;

/**
 * This class is used to find files for allure runner.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class FileLocator
{
    const COMMAND_TEMPLATE_LOCATE = 'locate %s';
    const COMMAND_TEMPLATE_FIND = 'find / -name %s';
    const COMMAND_TEMPLATE_WHICH = 'which %s';
    const COMMAND_TEMPLATE_MDFIND = 'mdfind %s';
    const COMMAND_TEMPLATE_WHERE = 'where %s';
    private $osFamily;
    public function __construct($osFamily)
    {
        $this->osFamily = $osFamily;
    }
    public function locateExecutableFile($fileName)
    {
        $process = new Process($this->getWhichCommand($fileName));
    }
    public function locateFile($fileName)
    {
        $process = new Process($this->getLocateCommand($fileName));
        $process->run();
        if ($process->getExitCode() === 0) {
            return $process->getOutput();
        }
        
    }
    private function getLocateCommand($fileName)
    {
        switch ($this->osFamily) {
            case Environment::FAMILY_WINDOWS:
                $template = self::COMMAND_TEMPLATE_WHERE;
                break;
            case Environment::FAMILY_MAC:
                // falling forward
            case Environment::FAMILY_LINUX:
                // falling forward
            case Environment::FAMILY_UNIX:
                $template = self::COMMAND_TEMPLATE_LOCATE;
                break;
            default:
                throw new UnknownOsFamilyException($this->osFamily);
        }
        return sprintf($template, $fileName);
    }
    private function getWhichCommand($fileName)
    {
        
    }
}
