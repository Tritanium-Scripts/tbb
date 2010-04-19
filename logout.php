<?php
/**
*
* Tritanium Bulletin Board 2 - logout.php
* Loggt einen User aus
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

//unset($_SESSION['tbb_user_id']);
//unset($_SESSION['tbb_user_pw']);

session_destroy();

header("Location: index.php?$MYSID"); exit;

?>