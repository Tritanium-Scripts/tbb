<?php
/**
 * Sets the "forum was updated" flag for each member file.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
if(!file_exists('core/DataPath.php'))
    exit('<b>ERROR:</b> DataPath.php not found!');
else
    include('core/DataPath.php');
if(!file_exists('members/'))
    exit('<b>ERROR:</b> members folder not found!');
echo('Setting update flag...<br /><br />');
foreach(glob(DATAPATH . 'members/[!0]*.xbb') as $curMember)
{
    $curMemberFile = file($curMember);
    if(trim($curMemberFile[11]) != '1')
    {
        $curMemberFile[11] = "1\n";
        file_put_contents($curMember, $curMemberFile, LOCK_EX);
    }
}
echo('Done!');
?>