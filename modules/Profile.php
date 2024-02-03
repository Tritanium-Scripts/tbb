<?php
/**
 * Manages an user profile incl. sending mails, vCard download and Steam achievements.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Profile extends PublicModule
{
    use Singleton, Mode, Errors;

    /**
     * Host name of Steam's content delivery network.
     *
     * Examples:
     *  cdn.steampowered.com
     *  cdn.akamai.steamstatic.com
     *  cdn.cloudflare.steamstatic.com
     *  steamcdn-a.akamaihd.net
     *
     * @var string Host name of Steam's CDN
     */
    private const STEAM_CDN_HOST_NAME = 'cdn.cloudflare.steamstatic.com';

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = ['' => 'ViewProfile',
        'profile' => 'ViewProfile',
        'edit' => 'EditProfile',
        'formmail' => 'SendMail',
        'vCard' => 'vCard',
        'EditProfileConfirmDelete' => 'EditProfileConfirmDelete',
        'viewAchievements' => 'ViewAchievements'];

    /**
     * Contains the requested user data to display or edit.
     *
     * @var bool|array User data or false if inapplicable
     */
    private $userData;

    /**
     * List of cached Steam games from this user profile.
     *
     * @var array Cached Steam games with names and logo link
     */
    private array $steamGames = [];

    /**
     * Use {@link file_get_contents()} for fetching achievements XML data.
     *
     * @var bool php.ini supporting allow_url_fopen
     */
    private ?bool $isFGC = null;

    /**
     * Use cURL for fetching achievements XML data.
     *
     * @var bool cURL extension loaded
     */
    private ?bool $isCURL = null;

    /**
     * Secret key for Steam web API access.
     *
     * @var string Exclusive Steam web API key
     */
    private ?string $webApiKey;

    /**
     * Loads user data and sets mode.
     *
     * @param string $mode Profile mode
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $userID = Functions::getValueFromGlobals($this->mode == 'formmail' ? 'target_id' : 'profile_id');
        $this->userData = Functions::getUserData($userID == '' ? Auth::getInstance()->getUserID() : $userID);
        //Detect method to fetch achievements data (if enabled)
        if(Config::getInstance()->getCfgVal('achievements') == 1)
        {
            $this->isFGC = ini_get('allow_url_fopen') == '1';
            if(!$this->isFGC)
                $this->isCURL = extension_loaded('curl');
            $this->webApiKey = Config::getInstance()->getCfgVal('web_api_key');
        }
    }

    /**
     * Displays or edits the user profile.
     */
    public function publicCall(): void
    {
        if(!Auth::getInstance()->isLoggedIn() && Config::getInstance()->getCfgVal('profile_mbli') == 1)
            Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        //Global guest or deleted check
        elseif(empty($this->userData) || $this->userData[4] == '5')
            Template::getInstance()->printMessage('user_does_not_exist');
        switch($this->mode)
        {
//(Still) EditProfile
            case 'refreshSteamGames':
            if(Config::getInstance()->getCfgVal('achievements') != 1)
                $this->errors[] = Language::getInstance()->getString('text_function_deactivated', 'Messages');
            //Use cached game information from last half hour
            elseif(file_exists($cacheFile = 'cache/' . $this->userData[1] . '-SteamGames.cache.php') && (filemtime($cacheFile) + 1800 > time()))
                include($cacheFile);
            //Load Steam games for user, if any (and class to handle XML data is available)
            elseif(!class_exists('DOMDocument', false) || (!$this->isFGC && !$this->isCURL))
                $this->errors[] = Language::getInstance()->getString('text_function_not_supported', 'Messages');
            elseif(empty($this->userData[18]))
                $this->errors[] = Language::getInstance()->getString('please_enter_your_steam_profile_name');
            elseif(!$this->refreshSteamGames($cacheFile))
                $this->errors[] = Language::getInstance()->getString('loading_steam_games_failed');
            //Output as JSON string
            header('Content-Type: application/json');
            echo('{"errors":[' . (!empty($this->errors) ? '"' . implode('","', $this->errors) . '"' : '],"values":['));
            $jsonGames = [];
            foreach($this->steamGames as $curSteamGame)
                $jsonGames[] = '{"gameID":"' . $curSteamGame[0] . '","gameLogo":"' . $curSteamGame[1] . '","gameName":"' . $curSteamGame[2] . '","gameSelected":' . (in_array($curSteamGame[0], $this->userData[19]) ? 'true' : 'false') . '}';
            exit(implode(',', $jsonGames) . ']}');
            break;

//EditProfile
            case 'edit':
            if(!Auth::getInstance()->isLoggedIn())
                Template::getInstance()->printMessage('profile_need_login');
            elseif(Auth::getInstance()->getUserID() != $this->userData[1] && !Auth::getInstance()->isAdmin())
                Template::getInstance()->printMessage('profile_no_access');
            NavBar::getInstance()->addElement(Language::getInstance()->getString('my_profile'), INDEXFILE . '?faction=profile&amp;mode=edit&amp;profile_id=' . $this->userData[1] . SID_AMPER);
            if(Functions::getValueFromGlobals('change') == '1')
            {
                //Delete acc?
                if(Functions::getValueFromGlobals('delete') != '')
                {
                    //Allowed?
                    switch(Config::getInstance()->getCfgVal('delete_profiles'))
                    {
                        case 2:
                        if($this->userData[5] < 1)
                            break;

                        case 0:
                        Template::getInstance()->printMessage('function_deactivated');
                        break;
                    }
                    //Confirmed?
                    if(Functions::getValueFromGlobals('confirm') == '1')
                    {
                        //Time to say goodbye
                        Functions::unlink('members/' . $this->userData[1] . '.xbb');
                        Functions::unlink('members/' . $this->userData[1] . '.pm');
                        if(Functions::file_exists('members/' . $this->userData[1] . '.ach'))
                            Functions::unlink('members/' . $this->userData[1] . '.ach');
                        $lockObj = Functions::getLockObject('vars/member_counter.var');
                        $lockObj->setFileContent($lockObj->getFileContent()-1);
                        //In case not an admin has deleted the user from "his own profile" (approx 99,9% of all cases, lol)
                        if($this->userData[1] == Auth::getInstance()->getUserID())
                        {
                            //Perform a logout "light"
                            unset($_SESSION['userID'], $_SESSION['userHash']);
                            //Notify other modules
                            WhoIsOnline::getInstance()->delete($this->userData[1]);
                            Auth::getInstance()->loginChanged();
                        }
                        Template::getInstance()->printMessage('account_deleted');
                    }
                    //Get confirmation
                    else
                    {
                        NavBar::getInstance()->addElement(Language::getInstance()->getString('delete_account'));
                        $this->mode = 'EditProfileConfirmDelete';
                    }
                }
                //Normal edit
                else
                {
                    //Check and update settings
                    if(($this->userData[3] = Functions::getValueFromGlobals('new_mail')) == '')
                        $this->errors[] = Language::getInstance()->getString('please_enter_your_mail');
                    elseif(!Functions::isValidMail($this->userData[3]))
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                    $this->userData[7] = Functions::nl2br(htmlspecialchars(trim(Functions::getValueFromGlobals('new_signatur', false))));
                    $this->userData[9] = Functions::getValueFromGlobals('new_hp');
                    $this->userData[10] = Functions::getValueFromGlobals('new_pic');
                    $this->userData[12] = htmlspecialchars(trim(Functions::getValueFromGlobals('new_realname')));
                    if(($this->userData[13] = Functions::getValueFromGlobals('new_icq')) != '' && !ctype_digit($this->userData[13]))
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_icq_number');
                    $this->userData[14][0] = Functions::getValueFromGlobals('new_mail1') == '1' ? '1' : '0';
                    $this->userData[14][1] = Functions::getValueFromGlobals('new_mail2') == '1' ? '1' : '0';
                    $this->userData[18] = trim(Functions::getValueFromGlobals('steamProfile'));
                    $this->userData[19] = Functions::getValueFromGlobals('steamGames');
                    if(empty($this->userData[19]) || empty($this->userData[18]))
                        $this->userData[19] = [];
                    if(Functions::strpos($this->userData[18], ' ') !== false)
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_steam_profile_name');
                    $this->userData[20] = Functions::getValueFromGlobals('ownTemplate');
                    $this->userData[21] = Functions::getValueFromGlobals('ownStyle');
                    $this->userData[22] = Functions::getTimestampFromGlobals('birthday');
                    if(!empty($this->userData[22]) && !Functions::isValidBirthday($this->userData[22]))
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_birthday');
                    $newPass = Functions::getValueFromGlobals('new_pw1');
                    if($newPass != Functions::getValueFromGlobals('new_pw2'))
                        $this->errors[] = Language::getInstance()->getString('new_passwords_do_not_match');
                    //Write updates?
                    if(empty($this->errors))
                    {
                        //Prepare for writing
                        $this->userData[14] = implode(',', $this->userData[14]);
                        $this->userData[19] = Functions::implodeByTab($this->userData[19]);
                        if(!empty($newPass))
                        {
                            //Hash new password and update session and cookie logins
                            $this->userData[2] = Functions::getHash($newPass);
                            //Only refresh if user is not admin and editing other profiles or current user updating himself
                            if(!Auth::getInstance()->isAdmin() || Auth::getInstance()->getUserID() == $this->userData[1])
                            {
                                $_SESSION['userHash'] = $this->userData[2];
                                if(isset($_COOKIE['cookie_xbbuser']))
                                    setcookie('cookie_xbbuser', $this->userData[1] . "\t" . $this->userData[2], time()+3600*24*365, Config::getInstance()->getCfgVal('path_to_forum'));
                            }
                        }
                        //Update to file
                        Functions::file_put_contents('members/' . $this->userData[1] . '.xbb', implode("\n", $this->userData));
                        //And done
                        Logger::getInstance()->log('%s edited profile from ID: ' . $this->userData[1], Logger::LOG_EDIT_PROFILE);
                        Template::getInstance()->printMessage('profile_saved', INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER, INDEXFILE . '?faction=profile&amp;mode=edit&amp;profile_id=' . $this->userData[1] . SID_AMPER, Functions::getMsgBackLinks());
                    }
                }
            }
            //Check for forum updates since user's last login (redir'd directly from Login module)
            if($this->userData[11] == '1' && Auth::getInstance()->getUserID() == $this->userData[1])
            {
                //Tell user via errors
                $this->errors[] = Language::getInstance()->getString('forum_was_updated_since_last_visit');
                //Change update flag
                $this->userData[11] = '0';
                //Implode back other data
                $this->userData[14] = implode(',', $this->userData[14]);
                $this->userData[19] = Functions::implodeByTab($this->userData[19]);
                Functions::file_put_contents('members/' . $this->userData[1] . '.xbb', implode("\n", $this->userData));
                //Reload
                $this->userData = Functions::getUserData($this->userData[1]);
            }
            //Prepare rank, regDate + sig
            $this->userData[2] = Functions::getRankImage($this->userData[4], $this->userData[5]); //Reuse "password slot"
            $this->userData[4] = Functions::getStateName($this->userData[4], $this->userData[5]);
            $this->userData[6] = Functions::formatDate($this->userData[6] . (Functions::strlen($this->userData[6]) == 6 ? '01000000' : ''));
            $this->userData[7] = Functions::br2nl($this->userData[7]);
            //Delete not needed data or: the template doesn't need to know these
            unset($this->userData[8], $this->userData[11], $this->userData[15], $this->userData[16]);
            //Prepare Steam games
            if(Config::getInstance()->getCfgVal('achievements') == 1 && !empty($this->userData[18]) && class_exists('DOMDocument', false) && ($this->isFGC || $this->isCURL))
            {
                if(file_exists($cacheFile = 'cache/' . $this->userData[1] . '-SteamGames.cache.php'))
                    include($cacheFile);
                elseif(!$this->refreshSteamGames($cacheFile))
                {
                    $this->userData[18] = ['profileID' => $this->userData[18]];
                    $this->errors[] = Language::getInstance()->getString('loading_steam_games_failed');
                }
                //Add selected state
                foreach($this->steamGames as &$curSteamGame)
                    $curSteamGame[] = in_array($curSteamGame[0], $this->userData[19]);
                $this->userData[19] = &$this->steamGames;
            }
            else
                $this->userData[18] = ['profileID' => ''];
            //Provide selectable templates and styles, if allowed
            if(Config::getInstance()->getCfgVal('select_tpls') == 1 || Config::getInstance()->getCfgVal('select_styles') == 1)
                Template::getInstance()->assign('templates', Template::getInstance()->getAvailableTpls());
            break;

//SendMail
            case 'formmail':
            NavBar::getInstance()->addElement([
                [sprintf(Language::getInstance()->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER],
                [Language::getInstance()->getString('send_mail'), INDEXFILE . '?faction=formmail&amp;target_id=' . $this->userData[1] . SID_AMPER]]);
            if(Config::getInstance()->getCfgVal('activate_mail') != 1)
                Template::getInstance()->printMessage('function_deactivated');
            elseif(!Auth::getInstance()->isLoggedIn() && Config::getInstance()->getCfgVal('formmail_mbli') == 1)
                Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
            elseif($this->userData[14][0] != '1')
                Template::getInstance()->printMessage('user_no_form_mails');
            //Process e-mail
            $senderMail = Functions::getValueFromGlobals('sender_email');
            $senderName = Functions::getValueFromGlobals('sender_name');
            $subject = Functions::getValueFromGlobals('subject');
            $message = Functions::getValueFromGlobals('message', false);
            if(Functions::getValueFromGlobals('send') == 'yes')
            {
                //Check input
                if(!Auth::getInstance()->isLoggedIn())
                {
                    if(empty($senderMail))
                        $this->errors[] = Language::getInstance()->getString('please_enter_your_mail');
                    elseif(!Functions::isValidMail($senderMail))
                        $this->errors[] = Language::getInstance()->getString('please_enter_a_valid_mail');
                    if(empty($senderName))
                        $this->errors[] = Language::getInstance()->getString('please_enter_your_name');
                }
                else
                {
                    $senderMail = Auth::getInstance()->getUserMail();
                    $senderName = Auth::getInstance()->getUserNick();
                }
                //Send it
                if(empty($this->errors))
                    Template::getInstance()->printMessage(Functions::sendMessage($this->userData[3], 'mail_from_user', $this->userData[0], $senderName, $senderMail, $subject, $message, Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login') ? 'mail_sent' : 'sending_mail_failed');
            }
            //Recipient data (assigned automatically via reusing $this->userData)
            $this->userData = array_slice($this->userData, 0, 2) + ['recipientName' => &$this->userData[0],
                'recipientID' => &$this->userData[1]];
            //Sender data
            Template::getInstance()->assign(['senderName' => $senderName,
                'senderMail' => $senderMail,
                'subject' => $subject,
                'message' => $message]);
            break;

//vCard
            case 'vCard':
            Logger::getInstance()->log('%s downloaded vCard from user ' . $this->userData[0] . ' (ID: ' . $this->userData[1] . ')', Logger::LOG_USER_TRAFFIC);
            WhoIsOnline::getInstance()->setLocation('vCard,' . $this->userData[1]);
            $vCard = "BEGIN:VCARD\r\nVERSION:4.0\r\nKIND:individual\r\nFN:" . $this->userData[12] . "\r\nBDAY:" . (!empty($this->userData[22])
                ? date('Y-m-d', $this->userData[22])
                : '') . "\r\nNICKNAME:" . $this->userData[0] . "\r\n" . ($this->userData[14][1] == '1'
                    ? 'EMAIL;TYPE=home:' . $this->userData[3] . "\r\n"
                    : '') . 'URL:' . $this->userData[9] . "\r\nX-GENERATOR:Tritanium Bulletin Board " . VERSION_PUBLIC . "\r\n" . (!empty($this->userData[13])
                        ? 'X-ICQ:' . $this->userData[13] . "\r\n"
                        : '') . 'REV:' . date(DATE_ISO8601, filemtime(DATAPATH . 'members/' . $this->userData[1] . '.xbb')) . "\r\n" . 'END:VCARD';
            header('Content-Disposition: attachment; filename=' . htmlspecialchars_decode($this->userData[0]) . '.vcf');
            header('Content-Length: ' . Functions::strlen($vCard));
            header('Content-Type: text/vcard; charset=UTF-8; name=' . htmlspecialchars_decode($this->userData[0]) . '.vcf');
            exit($vCard);
            break;

//ViewAchievements
            case 'viewAchievements':
            $game = intval(Functions::getValueFromGlobals('game'));
            NavBar::getInstance()->addElement([
                [sprintf(Language::getInstance()->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER],
                [Language::getInstance()->getString('steam_achievements'), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . '&amp;mode=viewAchievements&amp;game=' . $game . SID_AMPER]]);
            if(Config::getInstance()->getCfgVal('achievements') != 1)
                Template::getInstance()->printMessage('function_deactivated');
            elseif(empty($this->userData[18]))
                Template::getInstance()->printMessage('no_steam_games');
            elseif(!in_array($game, $this->userData[19]))
                Template::getInstance()->printMessage('steam_game_not_found');
            //Use cached achievements from last half hour
            elseif(file_exists($cacheFile = 'cache/' . $this->userData[1] . '-Achievements-' . $game . '.cache.php') && (filemtime($cacheFile) + 1800 > time()))
            {
                include($cacheFile);
                break;
            }
            elseif(!class_exists('DOMDocument', false) || (!$this->isFGC && !$this->isCURL))
                Template::getInstance()->printMessage('function_not_supported');
            if(empty($this->webApiKey))
            {
                $dom = new DOMDocument;
                if(!@$dom->loadXML(Functions::loadURL('https://steamcommunity.com/' . (ctype_digit($this->userData[18]) ? 'profiles/' : 'id/') . $this->userData[18] . '/stats/' . $game . '/?tab=achievements&l=' . Language::getInstance()->getString('steam_language') . '&xml=all', $this->isFGC, $this->isCURL)))
                    $this->errors[] = Language::getInstance()->getString('loading_achievements_failed');
                elseif($dom->getElementsByTagName('error')->length == 0)
                {
                    $achievementsClosed = $achievementsOpen = [];
                    $achievements = $dom->getElementsByTagName('achievement');
                    //Get achievements, sorted by open/close state
                    foreach($achievements as $curAchievement)
                        if($curAchievement->attributes->getNamedItem('closed')->nodeValue == '1')
                            $achievementsClosed[] = ['icon' => $curAchievement->getElementsByTagName('iconClosed')->item(0)->nodeValue,
                                'name' => htmlspecialchars($curAchievement->getElementsByTagName('name')->item(0)->nodeValue),
                                'description' => htmlspecialchars($curAchievement->getElementsByTagName('description')->item(0)->nodeValue),
                                'unlocked' => $curAchievement->getElementsByTagName('unlockTimestamp')->length == 1 ? Functions::utf8Encode(strftime(Language::getInstance()->getString('DATEFORMAT'), $curAchievement->getElementsByTagName('unlockTimestamp')->item(0)->nodeValue)) : ''];
                        else
                            $achievementsOpen[] = ['icon' => $curAchievement->getElementsByTagName('iconOpen')->item(0)->nodeValue,
                                'name' => htmlspecialchars($curAchievement->getElementsByTagName('name')->item(0)->nodeValue),
                                'description' => htmlspecialchars($curAchievement->getElementsByTagName('description')->item(0)->nodeValue)];
                    $done = count($achievementsClosed);
                    $achievements = ['name' => htmlspecialchars($dom->getElementsByTagName('gameName')->item(0)->nodeValue, ENT_QUOTES),
                        'logo' => $dom->getElementsByTagName('gameLogo')->item(0)->nodeValue,
                        'icon' => $dom->getElementsByTagName('gameIcon')->item(0)->nodeValue,
                        'numTotal' => $achievements->length,
                        'numClosed' => $done,
                        'numOpen' => count($achievementsOpen),
                        //Calculate progess
                        'percentClosed' => $achievements->length != '0' ? ($done / $achievements->length)*100 : 0,
                        'achievementsClosed' => $achievementsClosed,
                        'achievementsOpen' => $achievementsOpen];
                    Template::getInstance()->assign($achievements);
                    //Cache entire template assign code
                    Functions::file_put_contents($cacheFile, '<?php Template::getInstance()->assign(unserialize(\'' . Functions::str_replace("'", "\'", serialize($achievements)) . '\')); ?>', LOCK_EX, false, false);
                }
                else
                    foreach($dom->getElementsByTagName('error') as $curError)
                        $this->errors[] = $curError->nodeValue;
            }
            else
            {
                $playerId = $this->getSteamPlayerId();
                $achievements = $gameSchema = $ownedGames = [];
                if(!empty($playerId))
                    $achievements = $this->parseJson($this->getFromSteamWebApi('ISteamUserStats', 'GetPlayerAchievements', 1, ['steamid' => $playerId, 'appid' => $game, 'l' => Language::getInstance()->getString('steam_language')]));
                if(!empty($achievements) && $achievements['playerstats']['success'])
                    $gameSchema = $this->parseJson($this->getFromSteamWebApi('ISteamUserStats', 'GetSchemaForGame', 2, ['appid' => $game, 'l' => Language::getInstance()->getString('steam_language')]));
                if(!empty($gameSchema))
                {
                    //Overwrite missing or really odd game name provided by schema
                    $gameSchema['game']['gameName'] = $achievements['playerstats']['gameName'];
                    $ownedGames = $this->parseJson($this->getFromSteamWebApi('IPlayerService', 'GetOwnedGames', 1, ['input_json' => json_encode((object) ['steamid' => $playerId, 'include_appinfo' => true, 'include_played_free_games' => true, 'appids_filter' => [$game]])]));
                }
                if(empty($ownedGames) || $ownedGames['response']['game_count'] != 1)
                    $this->errors[] = Language::getInstance()->getString('loading_achievements_failed');
                else
                {
                    $achievementsClosed = $achievementsOpen = [];
                    $achievements = $achievements['playerstats']['achievements'];
                    //Get achievements, sorted by open/close state
                    foreach($achievements as $curAchievement)
                    {
                        //Look up corresponing achievement in the game schema
                        foreach($gameSchema['game']['availableGameStats']['achievements'] as $key => $curAchievementSchema)
                            if($curAchievementSchema['name'] === $curAchievement['apiname'])
                            {
                                $curAchievement += $curAchievementSchema;
                                unset($gameSchema['game']['availableGameStats']['achievements'][$key]);
                                break;
                            }
                        if($curAchievement['achieved'] == 1)
                            $achievementsClosed[] = ['icon' => $curAchievement['icon'],
                                'name' => htmlspecialchars($curAchievement['displayName']),
                                //Description tag is not always available (most likely hidden achievement)
                                'description' => isset($curAchievement['description']) ? htmlspecialchars($curAchievement['description']) : '',
                                //Existing unlock timestamp marked by no zero
                                'unlocked' => $curAchievement['unlocktime'] > 0 ? Functions::utf8Encode(strftime(Language::getInstance()->getString('DATEFORMAT'), $curAchievement['unlocktime'])) : ''];
                        else
                            $achievementsOpen[] = ['icon' => $curAchievement['icongray'],
                                'name' => htmlspecialchars($curAchievement['displayName']),
                                //Description tag is not always available (most likely hidden achievement)
                                'description' => isset($curAchievement['description']) ? htmlspecialchars($curAchievement['description']) : ''];
                    }
                    $done = count($achievementsClosed);
                    $achievements = ['name' => htmlspecialchars($gameSchema['game']['gameName'], ENT_QUOTES),
                        'logo' => 'https://' . Profile::STEAM_CDN_HOST_NAME . '/steam/apps/' . $game . '/capsule_184x69.jpg',
                        'icon' => 'https://' . Profile::STEAM_CDN_HOST_NAME . '/steamcommunity/public/images/apps/' . $game . '/' . $ownedGames['response']['games'][0]['img_icon_url'] . '.jpg',
                        'numTotal' => count($achievements),
                        'numClosed' => $done,
                        'numOpen' => count($achievementsOpen),
                        //Calculate progess
                        'percentClosed' => count($achievements) != 0 ? ($done / count($achievements))*100 : 0,
                        'achievementsClosed' => $achievementsClosed,
                        'achievementsOpen' => $achievementsOpen];
                    Template::getInstance()->assign($achievements);
                    //Cache entire template assign code
                    Functions::file_put_contents($cacheFile, '<?php Template::getInstance()->assign(unserialize(\'' . Functions::str_replace("'", "\'", serialize($achievements)) . '\')); ?>', LOCK_EX, false, false);
                }
            }
            break;

//ViewProfile
            case 'profile':
            default:
            NavBar::getInstance()->addElement(sprintf(Language::getInstance()->getString('view_profile_from_x'), $this->userData[0]), INDEXFILE . '?faction=profile&amp;profile_id=' . $this->userData[1] . SID_AMPER);
            //Check mail options
            if($this->userData[14][1] != '1')
                $this->userData[3] = false;
            $this->userData[14] = $this->userData[14][0] == '1';
            //Prepare rank
            $this->userData[2] = Functions::getRankImage($this->userData[4], $this->userData[5]); //Reuse "password slot"
            $this->userData[4] = Functions::getStateName($this->userData[4], $this->userData[5]);
            //Group stuff
            if(!empty($this->userData[15]))
            {
                $group = Functions::getGroupData($this->userData[15]);
                $this->userData[15] = $group[1];
                //Use the group's avatar if user has none
                if(empty($this->userData[10]))
                    $this->userData[10] = $group[2];
            }
            //Prepare avatar
            if(!empty($this->userData[10]))
            {
                $this->userData[10] = Functions::addHTTP($this->userData[10]);
                list($this->userData['avatarWidth'], $this->userData['avatarHeight']) = [Config::getInstance()->getCfgVal('avatar_width'), Config::getInstance()->getCfgVal('avatar_height')];
                if(Config::getInstance()->getCfgVal('use_getimagesize') == 1 && ($avatar = @getimagesize($this->userData[10])) != false)
                {
                    if($this->userData['avatarWidth'] > $avatar[0])
                        $this->userData['avatarWidth'] = $avatar[0];
                    if($this->userData['avatarHeight'] > $avatar[1])
                        $this->userData['avatarHeight'] = $avatar[1];
                }
            }
            //Joined x weeks ago
            $this->userData[8] = intval(($this->userData[11] = abs(time()-Functions::getTimestamp($this->userData[6] . '01000000'))) / 604800); //Reuse "forum access perms slot"
            //Posts per day
            $this->userData[11] = $this->userData[5] / ceil($this->userData[11] / 86400); //Reuse "forum update slot"
            //Format date + signature
            $this->userData[6] = Functions::formatDate($this->userData[6] . '01000000');
            $this->userData[7] = BBCode::getInstance()->parse(Functions::censor($this->userData[7]));
            //Load Steam games for user, if any (and class to handle XML data is available)
            if(Config::getInstance()->getCfgVal('achievements') == 1 && !empty($this->userData[18]) && !empty($this->userData[19]) && class_exists('DOMDocument', false) && ($this->isFGC || $this->isCURL))
            {
                //Use cached game information
                if(file_exists($cacheFile = 'cache/' . $this->userData[1] . '-SteamGames.cache.php'))
                    include($cacheFile);
                else
                    $this->refreshSteamGames($cacheFile);
                //Filter out not selected games
                $this->userData[19] = array_filter($this->steamGames, [$this, 'isSteamGameSelected']);
            }
            else
                $this->userData[18] = $this->userData[19] = '';
            break;
        }
        //Append profile ID for WIO location
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__), ['userData' => $this->userData,
            'errors' => $this->errors], null, ',' . $this->userData[1]);
    }

    /**
     * Performs a hard refresh of user's Steam games regardless of cache state.
     * Required preconditions are <b>not</b> checked!
     *
     * @param string $cacheFile Name of file to chache Steam games into
     * @return bool Refresh was successful
     */
    private function refreshSteamGames(string $cacheFile): bool
    {
        $refreshed = empty($this->webApiKey)
            ? $this->refreshSteamGamesByXmlData($cacheFile)
            : $this->refreshSteamGamesByWebApi($cacheFile);
        if($refreshed)
        {
            //Sort by display game name
            usort($this->steamGames, fn($game1, $game2) => strcmp($game1[2], $game2[2]));
            //Cache game data
            Functions::file_put_contents($cacheFile, '<?php $this->userData[18] = unserialize(\'' . serialize($this->userData[18]) . '\'); $this->steamGames = unserialize(\'' . serialize($this->steamGames) . '\'); ?>', LOCK_EX, false, false);
        }
        return $refreshed;
    }

    /**
     * Refreshed user's Steam profile and games by using (deprecated) community XML data.
     *
     * @param string $cacheFile Name of file to chache Steam games into
     * @return bool Refresh was successful
     * @link https://partner.steamgames.com/documentation/community_data
     */
    private function refreshSteamGamesByXmlData(string $cacheFile): bool
    {
        $source = Functions::loadURL('https://steamcommunity.com/' . (ctype_digit($this->userData[18]) ? 'profiles/' : 'id/') . $this->userData[18] . '/games/?tab=all&l=' . Language::getInstance()->getString('steam_language') . '&xml=1', $this->isFGC, $this->isCURL);
        if(empty($source))
            return false;
        $dom = new DOMDocument;
        if(!@$dom->loadXML($source))
            return false;
        $this->userData[18] = ['profileID' => $this->userData[18],
            'profileName' => $dom->getElementsByTagName('steamID')->item(0)->nodeValue];
        $this->steamGames = [];
        //Extract all Steam games from user
        foreach($dom->getElementsByTagName('game') as $curSteamGame)
        {
            //Only consider games with stats
            if($curSteamGame->getElementsByTagName('statsLink')->length == 0)
                continue;
            $this->steamGames[] = [$curSteamGame->getElementsByTagName('appID')->item(0)->nodeValue, //Game ID
                $curSteamGame->getElementsByTagName('logo')->item(0)->nodeValue, //Game logo
                htmlspecialchars($curSteamGame->getElementsByTagName('name')->item(0)->nodeValue, ENT_QUOTES)]; //Full game name
        }
        return true;
    }

    /**
     * Refreshed user's Steam profile and games by using web API.
     *
     * @param string $cacheFile Name of file to chache Steam games into
     * @return bool Refresh was successful
     * @link https://partner.steamgames.com/doc/webapi_overview
     * @link https://developer.valvesoftware.com/wiki/Steam_Web_API
     */
    private function refreshSteamGamesByWebApi(string $cacheFile): bool
    {
        $playerId = $this->getSteamPlayerId();
        if(empty($playerId))
            return false;
        //Fetch display name first
        $source = $this->parseJson($this->getFromSteamWebApi('ISteamUser', 'GetPlayerSummaries', 2, ['steamids' => $playerId]));
        if(empty($source) || empty($source['response']['players']))
            return false;
        $this->userData[18] = ['profileID' => $playerId,
            'profileName' => $source['response']['players'][0]['personaname']];
        //Fetch games afterwards
        $source = $this->parseJson($this->getFromSteamWebApi('IPlayerService', 'GetOwnedGames', 1, ['steamid' => $playerId, 'include_appinfo' => true, 'include_played_free_games' => true]));
        if(empty($source))
            return false;
        $this->steamGames = [];
        //Extract all Steam games from user
        foreach($source['response']['games'] as $curSteamGame)
        {
            //Only consider games with stats
            if(!($curSteamGame['has_community_visible_stats'] ?? false))
                continue;
            $this->steamGames[] = [$curSteamGame['appid'], //Game ID
                'https://' . Profile::STEAM_CDN_HOST_NAME . '/steam/apps/' . $curSteamGame['appid'] . '/capsule_184x69.jpg', //Game logo
                htmlspecialchars($curSteamGame['name'], ENT_QUOTES)]; //Full game name
        }
        return true;
    }

    /**
     * Returns Steam player ID of this profile.
     *
     * @return string 64-bit Steam ID or null
     */
    private function getSteamPlayerId(): ?string
    {
        //Having it already entered as such, just return it...
        if(ctype_digit($this->userData[18]))
            return $this->userData[18];
        //...otherwise resolve it against the web API
        $jsonArray = $this->parseJson($this->getFromSteamWebApi('ISteamUser', 'ResolveVanityURL', 1, ['vanityurl' => $this->userData[18]]));
        return !empty($jsonArray) && $jsonArray['response']['success'] == 1 ? $jsonArray['response']['steamid'] : null;
    }

    /**
     * Loads given JSON string by parsing and handling any errors.
     *
     * @param string $json JSON to parse
     * @return array Decoded JSON array or null
     */
    private function parseJson($json): ?array
    {
        if($json === false)
            return null;
        $jsonArray = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
        return json_last_error() == JSON_ERROR_NONE ? $jsonArray : null;
    }

    /**
     * Performs a GET operation on the Steam web API with the specified parameters.
     *
     * @param string $interface Name of interface to query
     * @param string $method Name of method to use
     * @param int $version Version number of method
     * @param array $params Optional parameters to append
     * @param string $format Optional different data format to use
     * @return string|bool Response from web API or false
     */
    private function getFromSteamWebApi(string $interface, string $method, int $version=1, array $params=[], string $format='json')
    {
        return @Functions::loadURL('https://api.steampowered.com/' . $interface . '/' . $method . '/v000' . $version . '/?key=' . $this->webApiKey . '&format=' . $format . '&' . http_build_query($params, '', '&'), $this->isFGC, $this->isCURL);
    }

    /**
     * Callback to return a Steam game is selected by user.
     *
     * @param array $curSteamGame Single entry from Steam game list
     * @return bool User selected provided Steam game for displaying achievements
     */
    private function isSteamGameSelected(array $curSteamGame): bool
    {
        return in_array($curSteamGame[0], $this->userData[19]);
    }
}
?>