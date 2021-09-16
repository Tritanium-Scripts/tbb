<?php
/**
 * Wraps PHP's normal string functions to its Multibyte counterparts and defining the final feature set of Functions class with mbstring extension support enabled.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2021 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.7
 */
class Functions extends FunctionsBasic
{
	/**
	 * Wraps PHP's {@link mail()} to Multibyte's {@link mb_send_mail()}.
	 */
	public static function mail($to, $subject, $message)
	{
		if(Main::getModule('Config')->getCfgVal('activate_mail') == 1)
		{
			//Strip and trim chars from forum name violating mail header syntax (RFC 2822)
			$forumName = trim(self::str_replace(array(',', ';', '@', '<', '>'), '', Main::getModule('Config')->getCfgVal('forum_name')));
			$isAccepted = @mb_send_mail($to, $subject, $message,
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
	 * PHP's {@link str_ireplace()}. No Multibyte version available.
	 */
	public static function str_ireplace($search, $replace, $subject, $count=null)
	{
		return str_ireplace($search, $replace, $subject, $count);
	}

	/**
	 * PHP's {@link str_replace()} with multi-dimensional array support. No Multibyte version available.
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
	 * Wraps PHP's {@link stripos()} to Multibyte's {@link mb_stripos()}.
	 */
	public static function stripos($haystack, $needle, $offset=null)
	{
		return mb_stripos($haystack, $needle, $offset);
	}

	/**
	 * Wraps PHP's {@link strlen()} to Multibyte's {@link mb_strlen()}.
	 */
	public static function strlen($string)
	{
		return mb_strlen($string);
	}

	/**
	 * Wraps PHP's {@link strpos()} to Multibyte's {@link mb_strpos()}.
	 */
	public static function strpos($haystack, $needle, $offset=null)
	{
		return mb_strpos($haystack, $needle, $offset);
	}

	/**
	 * Wraps PHP's {@link stripos()} to Multibyte's {@link mb_stripos()}.
	 */
	public static function strripos($haystack, $needle, $offset=null)
	{
		return mb_strripos($haystack, $needle, $offset);
	}

	/**
	 * Wraps PHP's {@link strtolower()} to Multibyte's {@link mb_strtolower()}.
	 */
	public static function strtolower($str)
	{
		return mb_strtolower($str);
	}

	/**
	 * Wraps PHP's {@link strtoupper()} to Multibyte's {@link mb_strtoupper()}.
	 */
	public static function strtoupper($string)
	{
		return mb_strtoupper($string);
	}

	/**
	 * Wraps PHP's {@link substr()} to Multibyte's {@link mb_substr()}.
	 */
	public static function substr($string, $start, $length=null)
	{
		return isset($length) ? mb_substr($string, $start, $length) : mb_substr($string, $start);
	}

	/**
	 * Wraps PHP's {@link substr_count()} to Multibyte's {@link mb_substr_count()}.
	 */
	public static function substr_count($haystack, $needle, $encoding=null)
	{
		return isset($encoding) ? mb_substr_count($haystack, $needle, $encoding) : mb_substr_count($haystack, $needle);
	}
}
?>