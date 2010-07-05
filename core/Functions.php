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
			mail($to, $subject, $message,
				'From: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'Reply-To: ' . Main::getModule('Config')->getCfgVal('forum_name') . ' <' . Main::getModule('Config')->getCfgVal('forum_email') . '>' . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				'Content-Type: text/plain; charset=UTF8');
			Main::getModule('Logger')->log('Mail sent to ' . $to, LOG_USER_TRAFFIC);
		}
	}

	/**
	 * PHP's {@link strlen()}.
	 */
	public static function strlen($string)
	{
		return strlen($string);
	}
}
?>