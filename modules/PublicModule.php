<?php
/**
 * Template for every implementing module which can be called "directly" from an user.
 * These public accessible modules have a corresponding language file.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
abstract class PublicModule
{
    /**
     * Loads language file for callee.
     */
    protected function __construct()
    {
        try
        {
            Language::getInstance()->parseFile(basename(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0]['file'], '.php'));
        }
        catch(InvalidArgumentException $e)
        {
            Logger::getInstance()->error(get_class($e) . ': ' . $e->getMessage());
        }
    }

    /**
     * Called publicly from an user.
     */
    public abstract function publicCall(): void;
}
?>