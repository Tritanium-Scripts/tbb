<?php
/**
 * Generic configuration getter and setter.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Config// extends Module
{
	/**
	 * The default configuration values.
	 *
	 * @var array Default values
	 */
	private static $cfgDefaults = array('/',
		'http://www.meindedomain.de/forum',
		'Meineseite',
		'http://www.meindedomain.de/',
		'kontakt@meinedomain.de',
		'Meinforum',
		'',
		'<p style="color:#FF0000; font-family:Verdana; font-size:medium; font-weight:bold;">Das Forum befindet sich im Moment im Umbau. Schauen sie in ein paar Minuten einfach nochmal vorbei!<br />Sorry, but this board is currently under construction. Try again in a few minutes!</p>',
		'+0100',
		'',
		3,
		1,
		1,
		-1,
		0,
		0,
		30,
		20,
		15,
		1,
		1,
		1,
		1,
		1,
		0,
		0,
		'Administrator',
		'Moderator',
		'Verbannt',
		'Gelöscht',
		6,
		5,
		1,
		15,
		1,
		1,
		1,
		1,
		'styles/standard.css',
		'95%',
		'0',
		'4',
		0,
		1,
		1,
		1,
		1,
		'64',
		'64',
		1,
		'languages/de-DE/',
		1,
		'admin@meinedomain.de',
		'admin@meinedomain.de',
		0,
		1,
		//New TBB 1.5 config values
		'std',
		0);

	/**
	 * Name of config file to work with.
	 *
	 * @var string Name of config file
	 */
	private static $cfgFile = 'vars/settings.var';

	/**
	 * The configuration identifiers.
	 *
	 * @var array Configuration keys
	 */
	private static $cfgKeys = array('path_to_forum',
		'address_to_forum',
		'site_name',
		'site_address',
		'site_contact',
		'forum_name',
		'forum_logo',
		'uc_message',
		'gmt_offset',
		'log_options',
		'warn_admin_fds',
		'close_forum_fds',
		'activate_registration',
		'max_registrations',
		'create_reg_pw',
		'uc',
		'topics_per_page',
		'posts_per_page',
		'wio_timeout',
		'wio',
		'show_site_creation_time',
		'show_board_stats',
		'show_lposts',
		'show_kats',
		'censored',
		'must_be_logged_in',
		'var_admin',
		'var_mod',
		'var_banned',
		'var_killed',
		'stars_admin',
		'stars_mod',
		'news_position',
		'topic_is_hot',
		'formmail_mbli',
		'activate_mlist',
		'nli_must_enter_name',
		'show_private_forums',
		'css_file',
		'twidth',
		'tspacing',
		'tpadding',
		'append_sid_url',
		'use_gzip_compression',
		'use_file_caching',
		'activate_ob',
		'use_getimagesize',
		'avatar_height',
		'avatar_width',
		'use_diskfreespace',
		'lng_folder',
		'activate_mail',
		'admin_email',
		'forum_email',
		'mail_admin_new_registration',
		'notify_new_replies',
		//New TBB 1.5 config values
		'default_tpl',
		'clickjacking');

	/**
	 * Loaded configuration values are stored here.
	 *
	 * @var array Loaded configuration values
	 */
	private $cfgValues = array();

	/**
	 * Loads configuration values.
	 */
	function __construct()
	{
		$this->cfgValues = array_combine(self::$cfgKeys, Functions::file_exists(self::$cfgFile) ? Functions::file(self::$cfgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : self::$cfgDefaults);
	}

	/**
	 * Alias for {@link getConfigValue()}.
	 */
	public function getCfgVal($key)
	{
		return $this->getConfigValue($key);
	}

	/**
	 * Returns a single configuration value.
	 *
	 * @param string $key Identifier of config value
	 * @return string Requested config value
	 */
	public function getConfigValue($key)
	{
		return isset($this->cfgValues[$key]) ? $this->cfgValues[$key] : false;
	}

	/**
	 * Alias for {@link setConfigValue()}
	 */
	public function setCfgVal($key, $value, $save=false)
	{
		$this->setConfigValue($key, $value, $save);
	}

	/**
	 * Sets a single configuration value. <b>Existing data will be overwritten!</b>
	 *
	 * @param string $key Identifier to access the value
	 * @param mixed $value Configuration entry
	 * @param bool $save Store all config values to the config file
	 */
	public function setConfigValue($key, $value, $save=false)
	{
		$this->cfgValues[$key] = $value;
		if($save)
			Functions::file_put_contents(self::$cfgFile, implode("\n", $this->cfgValues));
	}
}
?>