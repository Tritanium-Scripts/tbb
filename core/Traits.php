<?php
/**
 * Provides singleton pattern. The constructor should obviously be private or at least protected.
 */
trait Singleton
{
    /**
     * Returns the singleton instance of this class.
     *
     * @param string $mode Optional mode for not yet loaded module
     * @return Singleton Instance of this class
     */
    public static function getInstance(?string $mode=null): object
    {
        static $instance = null;
        //Late static binding magic *zomg*
        return $instance ?: $instance = (!isset($mode) ? new static() : new static($mode));
    }
}

/**
 * Provides the $mode variable.
 */
trait Mode
{
    /**
     * Contains mode to execute.
     *
     * @var string Mode to execute
     */
    private string $mode;
}

/**
 * Provides empty $errors variable.
 */
trait Errors
{
    /**
     * Detected errors during execution.
     *
     * @var array Error messages
     */
    private array $errors = [];
}
?>