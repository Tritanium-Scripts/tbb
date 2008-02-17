<?php
/**
*
* Tritanium Bulletin Board 2 - logout.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

//unset($_SESSION['tbb_user_id']);
//unset($_SESSION['tbb_user_pw']);

session_destroy();

header("Location: index.php?$MYSID"); exit;

?>