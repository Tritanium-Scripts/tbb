<?php
/**
 * Provides saver writing to files with lock management.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class LockObject
{
    /**
     * File pointer resource of current file.
     *
     * @var resource File handle
     */
    private $fp;

    /**
     * Open/close state of current file.
     *
     * @var bool Close state
     */
    private bool $isClosed = false;

    /**
     * Opens stated file for reading and writing with exclusive lock.
     *
     * @param string $filename Name/path of file
     * @return LockObject New instance of this class
     */
    function __construct(string $filename)
    {
        $this->fp = fopen($filename, 'r+');
        flock($this->fp, LOCK_EX);
    }

    /**
     * Closes the file and releases the lock.
     */
    function __destruct()
    {
        if(!$this->isClosed)
        {
            flock($this->fp, LOCK_UN);
            fclose($this->fp);
            $this->isClosed = true;
        }
    }

    /**
     * Returns the first line of opened file.
     *
     * @return string First read-in line of file
     */
    public function getFileContent(): string
    {
        rewind($this->fp); //Revert action from prior fgets()
        return fgets($this->fp);
    }

    /**
     * Sets first line of opened file.
     *
     * @param string $string Data to write
     * @param bool $destruct Close the file afterwards
     */
    public function setFileContent(string $string, bool $destruct=true): void
    {
        rewind($this->fp); //Revert action from fgets()
        fwrite($this->fp, $string);
        if($destruct)
            $this->__destruct();
    }
}
?>