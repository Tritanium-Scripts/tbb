<?php
/**
 * Wraps PHP's normal string functions to its Multibyte counterpart and defining the final feature set of Functions class with mbstring extension support enabled.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
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
			mb_send_mail($to, $subject, $message,
				'From: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'Reply-To: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				'Content-Type: text/plain; charset=' . Main::getModule('Language')->getString('encoding', 'Mails'));
			Main::getModule('Logger')->log('Mail sent to ' . $to, LOG_USER_TRAFFIC);
		}
	}

	/**
	 * PHP's {@link str_ireplace()}. No Multibyte version available.
	 */
	public static function str_ireplace($search, $replace, $subject, $count=null)
	{
		return str_ireplace($search, $replace, $subject, $count);
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
		return mb_substr($string, $start, $length);
	}
}
?>