<?php
/**
 * Wraps PHP's normal string functions to itself and defining the final feature set of Functions class with mbstring extension support disabled.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2021 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.7
 */
class Functions extends FunctionsBasic
{
	/**
	 * PHP's {@link mail()}.
	 */
	public static function mail($to, $subject, $message)
	{
		if(Main::getModule('Config')->getCfgVal('activate_mail') == 1)
		{
			//Strip and trim chars from forum name violating mail header syntax (RFC 2822)
			$forumName = trim(self::str_replace(array(',', ';', '@', '<', '>'), '', Main::getModule('Config')->getCfgVal('forum_name')));
			$isAccepted = @mail($to, $subject, $message,
				'From: ' . $forumName . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'Reply-To: ' . $forumName . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				'Content-Type: text/plain; charset=' . Main::getModule('Language')->getString('encoding', 'Mails'));
			Main::getModule('Logger')->log('Mail ' . ($isAccepted ? 'sent to ' : 'FAILED to sent to ') . $to, LOG_USER_TRAFFIC);
			return $isAccepted;
		}
		return false;
	}

	/**
	 * Sends an e-mail message.
	 *
	 * @param string $to Recipient address
	 * @param string $msgIndex Identifier part of message subject and text
	 * @param mixed $args,... Optional arguments to be replaced in message text
	 * @return bool Message was accepted for sending
	 */
	public static function sendMessage($to, $msgIndex, $args=null)
	{
		$temp = func_get_args();
		return self::mail($to, Main::getModule('Language')->getString('subject_' . $msgIndex, 'Mails'), vsprintf(Main::getModule('Language')->getString('message_' . $msgIndex), array_splice($temp, 2)));
	}

	/**
	 * PHP's {@link str_ireplace()}.
	 */
	public static function str_ireplace($search, $replace, $subject, $count=null)
	{
		return str_ireplace($search, $replace, $subject, $count);
	}

	/**
	 * PHP's {@link str_replace()} with multi-dimensional array support.
	 */
	public static function str_replace($search, $replace, $subject, $count=null)
	{
		if(is_array($subject))
		{
			array_walk_recursive($subject, function($value, $key) use($search, $replace, $count)
			{
				return str_replace($search, $replace, $value, $count);
			});
			return $subject;
		}
		else
			return str_replace($search, $replace, $subject, $count);
	}

	/**
	 * PHP's {@link stripos()}.
	 */
	public static function stripos($haystack, $needle, $offset=null)
	{
		return stripos($haystack, $needle, $offset);
	}

	/**
	 * PHP's {@link strlen()}.
	 */
	public static function strlen($string)
	{
		return strlen($string);
	}

	/**
	 * PHP's {@link strpos()}.
	 */
	public static function strpos($haystack, $needle, $offset=null)
	{
		return strpos($haystack, $needle, $offset);
	}

	/**
	 * PHP's {@link strripos()}.
	 */
	public static function strripos($haystack, $needle, $offset=null)
	{
		return strripos($haystack, $needle, $offset);
	}

	/**
	 * PHP's {@link strtolower()}.
	 */
	public static function strtolower($str)
	{
		return strtolower($str);
	}

	/**
	 * PHP's {@link strtoupper()}.
	 */
	public static function strtoupper($string)
	{
		return strtoupper($string);
	}

	/**
	 * PHP's {@link substr()}.
	 */
	public static function substr($string, $start, $length=null)
	{
		return isset($length) ? substr($string, $start, $length) : substr($string, $start);
	}

	/**
	 * PHP's {@link substr_count()}.
	 */
	public static function substr_count($haystack, $needle, $encoding=null)
	{
		return isset($encoding) ? substr_count($haystack, $needle, $encoding) : substr_count($haystack, $needle);
	}
}
?>