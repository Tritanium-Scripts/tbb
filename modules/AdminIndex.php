<?php
/**
 * Performs version check and displays the admin control panel.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class AdminIndex extends PublicModule
{
    use Singleton;

    /**
     * Checks for admin rights and displays the ACP.
     */
    public function publicCall(): void
    {
        Functions::accessAdminPanel();
        //If version check was done before, cache result to session
        if(!isset($_SESSION['isNewVersion']))
        {
            $latestRelease = Functions::loadURL('https://api.github.com/repos/Tritanium-Scripts/tbb/releases/latest');
            if($latestRelease !== false)
            {
                $latestRelease = json_decode($latestRelease, true);
                if(json_last_error() == JSON_ERROR_NONE && $latestRelease != null && isset($latestRelease['tag_name']))
                {
                    $latestReleaseVersion = trim($latestRelease['tag_name']);
                    //Ensure four parted version number for proper comparing
                    while(substr_count($latestReleaseVersion, '.') < 3)
                        $latestReleaseVersion .= '.0';
                    $_SESSION['isNewVersion'] = version_compare(VERSION_PRIVATE, $latestReleaseVersion) == -1;
                    //Also save release notes in case of new version
                    $_SESSION['versionNews'] = $_SESSION['isNewVersion'] && isset($latestRelease['body']) ? Functions::nl2br(trim($latestRelease['body'])) : '';
                }
            }
        }
        Template::getInstance()->printPage('AdminIndex', [
            'styleURL' => urlencode(Config::getInstance()->getCfgVal('address_to_forum') . '/' . Template::getInstance()->getTplDir() . Auth::getInstance()->getUserStyle()),
            'isNewVersion' => $_SESSION['isNewVersion'] ?? false,
            'versionNews' => $_SESSION['versionNews'] ?? '']);
    }
}
?>