<?php
/**
 * Manages file uploads.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Upload extends PublicModule
{
    use Singleton, Mode, Errors;

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
    private string $bbCode = '';

    /**
     * If a file was uploaded.
     *
     * @var bool File was uploaded
     */
    private bool $isUploaded = false;

    /**
     * Maximum of allowed filesize for uploaded file.
     *
     * @var int Max filesize in bytes
     */
    private int $maxFilesize;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['uploadFile' => 'Upload',
        'upload' => 'Upload'];

    /**
     * ID of HTML component to paste in URL of uploaded file.
     *
     * @var string HTML textarea ID
     */
    private string $targetBoxID;

    /**
     * Sets mode and file upload parameters.
     *
     * @param string $mode Mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->allowedExtensions = Config::getInstance()->getCfgVal('upload_allowed_ext');
        $this->allowedExtensions = $this->allowedExtensions != '' ? Functions::explodeByComma($this->allowedExtensions) : false;
        $this->targetBoxID = Functions::getValueFromGlobals('targetBoxID');
        //Prepare max filesizes
        $this->maxFilesize = intval(Config::getInstance()->getCfgVal('upload_max_filesize'));
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
    public function publicCall(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('upload_file', 'BBCode'), INDEXFILE . '?faction=uploadFile&amp;targetBoxID=' . $this->targetBoxID . SID_AMPER);
        if(Config::getInstance()->getCfgVal('enable_uploads') != 1)
            Template::getInstance()->printMessage('function_deactivated');
        if(!Auth::getInstance()->isLoggedIn())
            Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        WhoIsOnline::getInstance()->setLocation('Upload');
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
                    $this->errors[] = Language::getInstance()->getString('the_file_is_too_big');
                    @unlink($_FILES['uploadedFile']['tmp_name']);
                }
                //Check extension
                if($this->allowedExtensions != false && !in_array(Functions::strtolower(Functions::substr($_FILES['uploadedFile']['name'], Functions::strripos($_FILES['uploadedFile']['name'], '.')+1)), $this->allowedExtensions))
                {
                    $this->errors[] = Language::getInstance()->getString('file_type_is_not_allowed');
                    @unlink($_FILES['uploadedFile']['tmp_name']);
                }
                if(empty($this->errors))
                {
                    //Move to upload folder
                    $uploadName = 'uploads/' . gmdate('Y-m-d-H-i-s-') . basename($_FILES['uploadedFile']['name']);
                    if(!move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $uploadName))
                    {
                        $this->errors[] = Language::getInstance()->getString('upload_failed');
                        @unlink($_FILES['uploadedFile']['tmp_name']);
                    }
                    else
                    {
                        $this->isUploaded = true;
                        $this->bbCode = sprintf($this->isValidPicExt($_FILES['uploadedFile']['name']) ? '[img]%s[/img]' : '[url=%s]' . basename($_FILES['uploadedFile']['name']) . '[/url]', $uploadName);
                    }
                }
                break;

                //No file uploaded
                case UPLOAD_ERR_NO_FILE:
                $this->errors[] = Language::getInstance()->getString('please_select_a_file');
                break;

                //File too big
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                $this->errors[] = Language::getInstance()->getString('the_file_is_too_big');
                break;

                //Partial uploaded
                case UPLOAD_ERR_PARTIAL:
                $this->errors[] = Language::getInstance()->getString('file_uploaded_partially_try_again');
                break;

                //Any other error
                default:
                $this->errors[] = Language::getInstance()->getString('upload_failed');
                break;
            }
            break;
        }
        exit(Template::getInstance()->display(Functions::handleMode($this->mode, self::$modeTable, __CLASS__, 'uploadFile'), ['errors' => $this->errors,
            'allowedExtensions' => $this->allowedExtensions,
            'maxFilesize' => $this->maxFilesize/1024,
            'isUploaded' => $this->isUploaded,
            'bbCode' => $this->bbCode,
            'targetBoxID' => $this->targetBoxID]));
    }

    /**
     * Verfies a picture for known / supported extension.
     *
     * @param mixed $filename Name of image file with extension
     * @return bool Valid / (browser) supported image file
     */
    private function isValidPicExt(string $filename): bool
    {
        return (bool) preg_match("/(.*)\.(jpg|jpeg|gif|png|bmp|webp)/i", $filename);
    }
}
?>