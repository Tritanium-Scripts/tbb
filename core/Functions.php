<?php
/**
 * Wraps PHP's normal string functions to itself and defining the final feature set of Functions class with mbstring extension support disabled.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Functions extends CoreFunctions
{
    /**
     * PHP's {@link mail()}.
     */
    public static function mail(string $to, string $subject, string $message): bool
    {
        if(Config::getInstance()->getCfgVal('activate_mail') == 1)
        {
            //Strip and trim chars from forum name violating mail header syntax (RFC 2822)
            $forumName = trim(self::str_replace([',', ';', '@', '<', '>'], '', Config::getInstance()->getCfgVal('forum_name')));
            $isAccepted = @mail($to, $subject, $message,
                'From: ' . $forumName . ' <' . Config::getInstance()->getCfgVal('forum_email') . '>' . "\r\n" .
                'Reply-To: ' . $forumName . ' <' . Config::getInstance()->getCfgVal('forum_email') . '>' . "\r\n" .
                'X-Mailer: PHP/' . phpversion() . "\r\n" .
                'Content-Type: text/plain; charset=' . Language::getInstance()->getString('encoding', 'Mails'));
            Logger::getInstance()->log('Mail ' . ($isAccepted ? 'sent to ' : 'FAILED to send to ') . $to, Logger::LOG_USER_TRAFFIC);
            return $isAccepted;
        }
        return false;
    }

    /**
     * Sends an email message.
     *
     * @param string $to Recipient address
     * @param string $msgIndex Identifier part of message subject and text
     * @param mixed $args,... Optional arguments to be replaced in message text
     * @return bool Message was accepted for sending
     */
    public static function sendMessage(string $to, string $msgIndex, mixed ...$args): bool
    {
        return self::mail($to, Language::getInstance()->getString('subject_' . $msgIndex, 'Mails'), vsprintf(Language::getInstance()->getString('message_' . $msgIndex), $args));
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
            array_walk_recursive($subject, fn($value, $key): string => str_replace($search, $replace, $value, $count));
            return $subject;
        }
        else
            return str_replace($search, $replace, $subject, $count);
    }

    /**
     * PHP's {@link stripos()}.
     */
    public static function stripos($haystack, $needle, $offset=0)
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
    public static function strpos($haystack, $needle, $offset=0)
    {
        return strpos($haystack, $needle, $offset);
    }

    /**
     * PHP's {@link strripos()}.
     */
    public static function strripos($haystack, $needle, $offset=0)
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

    /**
     * PHP's {@link utf8_encode()} provided it hasn't been removed yet. Some appropriate fallback otherwise.
     *
     * @param string $string An ISO-8859-1 string
     * @return string UTF-8 translation
     */
    public static function utf8Encode(string $string): string
    {
        self::$cache['utf8_encode'] ??= function_exists('utf8_encode');
        return self::$cache['utf8_encode'] ? @utf8_encode($string) : iconv('ISO-8859-1', 'UTF-8', $string);
    }

    /**
     * PHP's {@link utf8_decode()} provided it hasn't been removed yet. Some appropriate fallback otherwise.
     *
     * @param string $string A UTF-8 encoded string
     * @return string ISO-8859-1 translation
     */
    public static function utf8Decode(string $string): string
    {
        self::$cache['utf8_decode'] ??= function_exists('utf8_decode');
        return self::$cache['utf8_decode'] ? @utf8_decode($string) : iconv('UTF-8', 'ISO-8859-1', $string);
    }
}
?>