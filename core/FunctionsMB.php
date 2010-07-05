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
	 * Wraps PHP's {@link strlen()} to Multibyte's {@link mb_strlen()}.
	 */
	public static function strlen($string)
	{
		return mb_strlen($string);
	}
}
?>