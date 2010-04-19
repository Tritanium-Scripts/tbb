<?php
/**
*
* Tritanium Bulletin Board 2 - logout.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

//unset($_SESSION['tbb_user_id']);
//unset($_SESSION['tbb_user_pw']);

session_destroy();

header("Location: index.php?$MYSID"); exit;

?>