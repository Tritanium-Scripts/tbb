<?php
/**
 * Wraps PHP's normal string functions to itself and defining the final feature set of Functions class with mbstring extension support disabled.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
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
			$isAccepted = @mail($to, $subject, $message,
				'From: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'Reply-To: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
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
	 * PHP's {@link str_replace()}.
	 */
	public static function str_replace($search, $replace, $subject, $count=null)
	{
		return str_replace($search, $replace, $subject, $count);
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
}
?>