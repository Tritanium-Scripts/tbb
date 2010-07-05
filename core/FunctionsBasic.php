<?php
/**
 * Various static functions and wrappers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class FunctionsBasic
{
	/**
	 * Counter for file accesses.
	 *
	 * @var int Amount of file reading and writing
	 */
	private static $fileCounter = 0;

	/**
	 * Checks current IP for access permission.
	 *
	 * @param int $forumID Only check for a specific forum, whole board otherwise
	 * @return bool|int Access permission granted or ban endtime
	 */
	public static function checkIPAccess($forumID=-1)
	{
		foreach(self::file('vars/ip.var') as $curIP)
		{
			$curIP = explode("\t", $curIP);
			if($curIP[0] == $_SERVER['REMOTE_ADDR'] && $curIP[2] == $forumID && ($curIP[1] > time() || $curIP[1] == '-1'))
				return (int) $curIP[1];
		}
		return true;
	}

	/**
	 * Extending PHP's {@link file()} with file counting, trim and global data path.
	 */
	public static function file($filename, $flags=null, $context=null)
	{
		self::$fileCounter++;
		return array_map('trim', file(DATAPATH . $filename, $flags, $context));
	}

	/**
	 * Extending PHP's {@link file_get_contents()} with file counting and global data path.
	 */
	public static function file_get_contents($filename, $flags=null, $context=null, $offset=null, $maxlen=null)
	{
		self::$fileCounter++;
		return file_get_contents(DATAPATH . $filename, $flags, $context, $offset, $maxlen);
	}

	/**
	 * Extending PHP's {@link file_put_contents()} with file counting and global data path.
	 */
	public static function file_put_contents($filename, $data, $flags=LOCK_EX, $context=null)
	{
		self::$fileCounter++;
		return file_put_contents(DATAPATH . $filename, $data, $flags, $context);
	}

	/**
	 * Returns amount of file accesses.
	 *
	 * @return int File counter
	 */
	public static function getFileCounter()
	{
		return self::$fileCounter;
	}

	/**
	 * Applies {@link stripslashes()} recursively on arrays as well.
	 *
	 * @param mixed $value Input value(s) to strip backslashes off
	 * @return mixed Input value(s) with backslashes stripped off
	 */
	public static function stripSlashesDeep($value)
	{
		return is_array($value) ? array_map(array('Functions', 'stripSlashesDeep'), $value) : stripslashes($value);
	}
}
?>