<?php
/**
*
* Tritanium Bulletin Board 2 - logout.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

//unset($_SESSION['tbb_user_id']);
//unset($_SESSION['tbb_user_pw']);

session_destroy();

header("Location: index.php?$MYSID"); exit;

?>