<?php
/**
 * Manages file uploads.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Upload implements Module
{
	/**
	 * Contains allowed file types to upload.
	 *
	 * @var array|bool Allowed file extensions or false
	 */
	private $allowedExtensions;

	/**
	 * BBCode of uploaded file to insert in post.
	 *
	 * @var string BBCode to insert
	 */
	private $bbCode = '';

	/**
	 * Detected errors during upload actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * If a file was uploaded.
	 *
	 * @var bool File was uploaded
	 */
	private $isUploaded = false;

	/**
	 * Maximum of allowed filesize for uploaded file.
	 *
	 * @var int Max filesize in bytes
	 */
	private $maxFilesize;

	/**
	 * Contains mode to execute.
	 *
	 * @var string Upload mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('uploadFile' => 'Upload',
		'upload' => 'Upload');

	private $targetBoxID;

	/**
	 * Sets mode and file upload parameters.
	 * 
	 * @param string $mode Mode
	 * @return Upload New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->allowedExtensions = ($this->allowedExtensions = Main::getModule('Config')->getCfgVal('upload_allowed_ext')) != '' ? Functions::explodeByComma($this->allowedExtensions) : false;
		$this->targetBoxID = Functions::getValueFromGlobals('targetBoxID');
		//Prepare max filesizes
		$this->maxFilesize = intval(Main::getModule('Config')->getCfgVal('upload_max_filesize'));
		$maxPHPSize = ini_get('upload_max_filesize');
		//Convert PHP shorthand bytes to real bytes
		switch(Functions::substr($maxPHPSize, -1))
		{
			//Credits to this convert function goes also to Stas Trefilov, OpteamIS:
			//http://de.php.net/manual/de/function.ini-get.php#96996
			case 'M':
			case 'm':
			$maxPHPSize = Functions::substr($maxPHPSize, 0, -1)*1048576;
			break;

			case 'K':
			case 'k':
			$maxPHPSize = Functions::substr($maxPHPSize, 0, -1)*1024;
			break;

			//New since PHP 5.1
			case 'G':
			case 'g':
			$maxPHPSize = Functions::substr($maxPHPSize, 0, -1)*1073741824;
			break;
		}
		//Now detect the actual limit (geez!)
		$this->maxFilesize = empty($this->maxFilesize) ? $maxPHPSize : ($this->maxFilesize > $maxPHPSize ? $maxPHPSize : $this->maxFilesize);
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('upload_file', 'BBCode'), INDEXFILE . '?faction=uploadFile&amp;targetBoxID=' . $this->targetBoxID . SID_AMPER);
		if(Main::getModule('Config')->getCfgVal('enable_uploads') != 1)
			Main::getModule('Template')->printMessage('function_deactivated');
		if(!Main::getModule('Auth')->isLoggedIn())
			Main::getModule('Template')->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
		Main::getModule('WhoIsOnline')->setLocation('Upload');
		switch($this->mode)
		{
			case 'upload':
			switch($_FILES['uploadedFile']['error'])
			{
				//File upload OK
				case UPLOAD_ERR_OK:
				//Check size
				if($_FILES['uploadedFile']['size'] > $this->maxFilesize)
				{
					$this->errors[] = Main::getModule('Language')->getString('the_file_is_too_big');
					@unlink($_FILES['uploadedFile']['tmp_name']);
				}
				//Check extension
				if($this->allowedExtensions != false && !in_array(Functions::substr($_FILES['uploadedFile']['name'], Functions::strripos($_FILES['uploadedFile']['name'], '.')+1), $this->allowedExtensions))
				{
					$this->errors[] = Main::getModule('Language')->getString('file_type_is_not_allowed');
					@unlink($_FILES['uploadedFile']['tmp_name']);
				}
				if(empty($this->errors))
				{
					//Move to upload folder
					if(!move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $uploadName = 'uploads/' . gmdate('Y-m-d-H-i-s-') . $_FILES['uploadedFile']['name']))
						$this->errors[] = Main::getModule('Language')->getString('upload_failed');
					else
					{
						$this->isUploaded = true;
						$this->bbCode = sprintf($this->isValidPicExt($_FILES['uploadedFile']['name']) ? '[img]%s[/img]' : '[url=%s]' . $_FILES['uploadedFile']['name'] . '[/url]', $uploadName);
					}
				}
				break;

				//No file uploaded
				case UPLOAD_ERR_NO_FILE:
				$this->errors[] = Main::getModule('Language')->getString('please_select_a_file');
				break;

				//File too big
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
				$this->errors[] = Main::getModule('Language')->getString('the_file_is_too_big');
				break;

				//Partial upload
				case UPLOAD_ERR_PARTIAL:
				$this->errors[] = Main::getModule('Language')->getString('file_uploaded_partially_try_again');
				break;
			}
			break;
		}
		exit(Main::getModule('Template')->display(self::$modeTable[$this->mode], array('errors' => $this->errors,
			'allowedExtensions' => $this->allowedExtensions,
			'maxFilesize' => $this->maxFilesize/1024,
			'isUploaded' => $this->isUploaded,
			'bbCode' => $this->bbCode,
			'targetBoxID' => $this->targetBoxID)));
	}

	/**
	 * Verfies a picture for known / supported extension.
	 * 
	 * @param mixed $filename Name of image file with extension
	 * @return bool Valid / supported image file
	 */
	private function isValidPicExt($filename)
	{
		return (bool) preg_match("/(.*)\.(jpg|jpeg|gif|png|bmp)/i", $filename);
	}
}
?>