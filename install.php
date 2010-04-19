<?php
/**
*
* Tritanium Bulletin Board 2 - install.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

if(file_exists('dbconfig.php')) {
	header("Location: index.php"); exit;
}

require_once('functions.php');

session_start();
session_name('sid');
$MYSID = 'sid='.session_id();

switch(@$_GET['step']) {
	default:
		$p_language = isset($_POST['p_language']) ? $_POST['p_language'] : '';

		if(isset($_GET['doit'])) {
			if(is_dir('language/'.$p_language) == TRUE) {
				$_SESSION['language'] = $p_language;
				header("Location: install.php?step=2&$MYSID"); exit;
			}
		}

		?>
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			 <title>Tritanium Bulletin Board 2 - Installation</title>
			 <style type="text/css">
			 <!--
			  table.tbl {
			   background-color:#000000;
			  }

			  td.tdwhite {
			   background-color:#FFFFFF;
			  }

			  th.thwhite {
			   background-color:#FFFFFF;
			  }

			  .fontnorm {
			   font-family:verdana,arial;
			   color:#000000;
			   font-size:10pt;
			  }

			  input.formbutton {
			   border:1px #000000 solid;
			   font-size:10px;
			   font-family:verdana,arial;
			  }
			 -->
			 </style>
			</head>
			<body>
			 <form method="post" action="install.php?step=1&amp;doit=1&amp;<?php echo $MYSID; ?>">
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 1</span></th></tr>
			 <tr>
			  <td valign="top" width="25%" class="tdwhite"><span class="fontnorm">Bitte w&auml;hlen Sie Ihre Sprache:<br />Please select your language:</span></td>
			  <td valign="top" width="75%" class="tdwhite"><select name="p_language">
		<?php

		$dp = opendir('language');
		while($akt_item = readdir($dp)) {
			if($akt_item != '.' && $akt_item != '..' && is_dir('language/'.$akt_item) == TRUE) {
				include('language/'.$akt_item.'/lng_install.php');
				echo '<option value="'.$akt_item.'">'.$lng['language_info'].'</option>';
			}
		}
		closedir($dp);

		?>
			  </select></td>
			 </tr>
			 <tr><td align="center" colspan="2" class="tdwhite"><input class="formbutton" type="submit" value="weiter / next" />&nbsp;&nbsp;&nbsp;<input class="formbutton" type="reset" value="zur&uuml;cksetzen / reset" /></td></tr>
			 </table>
			 </form>
			</body>
			</html>
		<?php
	break;

	case '2':
		include_once('language/'.$_SESSION['language'].'/lng_install.php');

		$p_dbserver = isset($_POST['p_dbserver']) ? $_POST['p_dbserver'] : '';
		$p_dbname = isset($_POST['p_dbname']) ? $_POST['p_dbname'] : '';
		$p_dbuser = isset($_POST['p_dbuser']) ? $_POST['p_dbuser'] : '';
		$p_dbpassword = isset($_POST['p_dbpassword']) ? $_POST['p_dbpassword'] : '';
		$p_tableprefix = isset($_POST['p_tableprefix']) ? $_POST['p_tableprefix'] : 'tbb2_';

		$p_dbtype = 'mysql';
		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_dbserver) == '') $error = $lng['error_no_database_server'];
			elseif(trim($p_dbuser) == '') $error = $lng['error_no_database_user'];
			elseif(trim($p_dbname) == '') $error = $lng['error_no_database_name'];
			else{
				switch($p_dbtype) {
					case 'mysql':
						include_once('db/mysql.class.php');
					break;
				}

				$db = new db;
				if($db->connect($p_dbserver,$p_dbuser,$p_dbpassword) == FALSE) $error = sprintf($lng['error_connecting_database_server'],$db->error());
				elseif($db->select_db($p_dbname) == FALSE) $error = sprintf($lng['error_invalid_unknown_database_name'],$db->error());
				elseif(preg_match('/^[a-z0-9_]{0,}$/i',$p_tableprefix) == FALSE) $error = $lng['error_invalid_table_prefix'];
				else {
					$_SESSION['dbtype'] = $p_dbtype;
					$_SESSION['dbserver'] = $p_dbserver;
					$_SESSION['dbuser'] = $p_dbuser;
					$_SESSION['dbname'] = $p_dbname;
					$_SESSION['dbpassword'] = $p_dbpassword;
					$_SESSION['tableprefix'] = $p_tableprefix;

					header("Location: install.php?step=3&$MYSID"); exit;
				}
			}
		}

		?>
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			 <title>Tritanium Bulletin Board 2 - Installation</title>
			 <style type="text/css">
			 <!--
			  table.tbl {
			   background-color:#000000;
			  }

			  td.tdwhite {
			   background-color:#FFFFFF;
			  }

			  th.thwhite {
			   background-color:#FFFFFF;
			  }

			  .fontnorm {
			   font-family:verdana,arial;
			   color:#000000;
			   font-size:10pt;
			  }

			  .fonterror {
			   font-family:verdana,arial;
			   color:red;
			   font-size:10pt;
			  }

			  input.formbutton {
			   border:1px #000000 solid;
			   font-size:10px;
			   font-family:verdana,arial;
			  }
			 -->
			 </style>
			</head>
			<body>
			 <form method="post" action="install.php?step=2&amp;doit=1&amp;<?php echo $MYSID; ?>">
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 2</span></th></tr>
			 <tr><td class="tdwhite" colspan="2"><span class="fontnorm"><?php echo $lng['db_access_data_info']; ?></span></tr>
		<?php

		if($error != '') echo '<tr><td colspan="2" class="tdwhite"><span class="fonterror"><b>'.$error.'</b></span></td></tr>';

		?>
			 <tr>
			  <td class="tdwhite" width="15%"><span class="fontnorm"><b><?php echo $lng['Database_server']; ?>:</b><span></td>
			  <td class="tdwhite" width="85%"><input type="text" name="p_dbserver" value="<?php echo $p_dbserver; ?>" size="30" /></td>
			 </tr>
			 <tr>
			  <td class="tdwhite" width="15%"><span class="fontnorm"><b><?php echo $lng['Database_user']; ?>:</b><span></td>
			  <td class="tdwhite" width="85%"><input type="text" name="p_dbuser" value="<?php echo $p_dbuser; ?>" size="30" /></td>
			 </tr>
			 <tr>
			  <td class="tdwhite" width="15%"><span class="fontnorm"><b><?php echo $lng['Database_password']; ?>:</b><span></td>
			  <td class="tdwhite" width="85%"><input type="password" name="p_dbpassword" value="<?php echo $p_dbpassword; ?>" size="30" /></td>
			 </tr>
			 <tr>
			  <td class="tdwhite" width="15%"><span class="fontnorm"><b><?php echo $lng['Database_name']; ?>:</b><span></td>
			  <td class="tdwhite" width="85%"><input type="text" name="p_dbname" value="<?php echo $p_dbname; ?>" size="30" /></td>
			 </tr>
			 <tr>
			  <td class="tdwhite" width="15%"><span class="fontnorm"><b><?php echo $lng['Table_prefix']; ?>:</b><span></td>
			  <td class="tdwhite" width="85%"><input type="text" name="p_tableprefix" value="<?php echo $p_tableprefix; ?>" size="10" /></td>
			 </tr>
			 <tr><td align="center" colspan="2" class="tdwhite"><input class="formbutton" type="submit" value="weiter / next" />&nbsp;&nbsp;&nbsp;<input class="formbutton" type="reset" value="zur&uuml;cksetzen / reset" /></td></tr>
			 </table>
			 </form>
			</body>
			</html>
		<?php
	break;

	case '3':
		include_once('language/'.$_SESSION['language'].'/lng_install.php');

		switch($_SESSION['dbtype']) {
			case 'mysql':
				include_once('db/mysql.class.php');
				$scheme_file = @fread(@fopen('db/mysql.scheme.sql','rb'),@filesize('db/mysql.scheme.sql'));
				$basic_file = @fread(@fopen('db/mysql.basic.sql','rb'),@filesize('db/mysql.basic.sql'));
			break;
		}

		$db = new db;
		if($db->connect($_SESSION['dbserver'],$_SESSION['dbuser'],$_SESSION['dbpassword']) == FALSE) die(sprintf($lng['error_connecting_database_server'],$db->error()));
		elseif($db->select_db($_SESSION['dbname']) == FALSE) die(sprintf($lng['error_invalid_unknown_database_name'],$db->error()));
		elseif(preg_match('/^[a-z0-9_]{0,}$/i',$_SESSION['tableprefix']) == FALSE) die($lng['error_invalid_table_prefix']);

		?>
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
			<html>
			<head>
			 <title>Tritanium Bulletin Board 2 - Installation</title>
			 <style type="text/css">
			 <!--
			  table.tbl {
			   background-color:#000000;
			  }

			  td.tdwhite {
			   background-color:#FFFFFF;
			  }

			  th.thwhite {
			   background-color:#FFFFFF;
			  }

			  .fontnorm {
			   font-family:verdana,arial;
			   color:#000000;
			   font-size:10pt;
			  }

			  .fonterror {
			   font-family:verdana,arial;
			   color:red;
			   font-size:10pt;
			  }

			  .fontred {
			   font-family:verdana,arial;
			   color:red;
			   font-size:10pt;
			  }

			  .fontgreen {
			   font-family:verdana,arial;
			   color:green;
			   font-size:10pt;
			  }

			  input.formbutton {
			   border:1px #000000 solid;
			   font-size:10px;
			   font-family:verdana,arial;
			  }
			 -->
			 </style>
			</head>
			<body>
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 3</span></th></tr>
			 <tr>
			  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $lng['Creating_tables']; ?></span></td>
			  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
		<?php

		$error = FALSE;

		$scheme_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$scheme_file);
		$db->sql_split($scheme_file);
		while(list(,$akt_query) = each($db->sql_queries)) {
			if(!$db->query($akt_query)) {
				$error = TRUE;
				break;
			}
		}

		if($error == TRUE) echo '<span class="fontred">'.$lng['error_creating_tables'].'<br /><b>'.$db->error().'</b></span>';
		else echo '<span class="fontgreen">'.$lng['successful'].'</span>';

		?>
			 </span></td>
			 </tr>
		<?php

		if($error == FALSE) {
			?>
				 <tr>
				  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $lng['Creating_config_file']; ?></span></td>
				  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
			<?php

			$errortext = '';

			if(!$fp = @fopen('dbconfig.php','wb')) {
				$errortext = $lng['Cannot_open_config_file'];
				$error = 'TRUE';
			}
			elseif(@fwrite($fp,"<?php\n\n/*\n*\n* Automatisch erstellt von TBB. Nicht aendern oder loeschen!\n*\n*/\n\n\$CONFIG['db_type'] = '".$_SESSION['dbtype']."';\n\n\$CONFIG['db_server'] = '".$_SESSION['dbserver']."';\n\$CONFIG['db_user'] = '".$_SESSION['dbuser']."';\n\$CONFIG['db_password'] = '".$_SESSION['dbpassword']."';\n\$CONFIG['db_name'] = '".$_SESSION['dbname']."';\n\ndefine('TBLPFX','".$_SESSION['tableprefix']."');\n\n?>") == FALSE) {
				$errortext = $lng['Cannot_write_config_file'];
				$error = 'TRUE';
			}
			@fclose($fp);

			$chmod_error = FALSE;
			if($error == FALSE) {
				if(@chmod('dbconfig.php',0755) == FALSE) $chmod_error = TRUE;
			}

			if($error == TRUE) echo '<span class="fontred">'.$lng['error_creating_config_file'].'<br /><b>'.$errortext.'</b></span>';
			else echo '<span class="fontgreen">'.$lng['successful'].'</span>';


			?>
				  </span></td>
				 </tr>
			<?php

			if($error == FALSE) {

				?>
					 <tr>
					  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $lng['Inserting_basic_data']; ?></span></td>
					  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
				<?php

				$basic_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$basic_file);
				$db->sql_split($basic_file);
				while(list(,$akt_query) = each($db->sql_queries)) {
					if(!$db->query($akt_query)) {
						$error = TRUE;
						break;
					}
				}

				if($error == TRUE) echo '<span class="fontred">'.$lng['error_inserting_basic_data'].'<br /><b>'.$db->error().'</b></span>';
				else {
					$db->query("INSERT INTO ".$_SESSION['tableprefix']."config (config_name,config_value) VALUES ('standard_language','".$_SESSION['language']."')");
					echo '<span class="fontgreen">'.$lng['successful'].'</span>';
				}


				?>
					  </span></td>
					 </tr>
				<?php

				if($error == FALSE) {
					if($chmod_error == TRUE) {
						?>
							<tr><td class="tdwhite" colspan="2"><span class="fontnorm"><?php echo $lng['Cannot_set_chmod']; ?></span></td></tr>
						<?php
					}
					?>
						</table>
						<br />
						<table class="tbl" cellpadding="3" cellspacing="1" width="100%">
						<tr><td class="tdwhite"><span class="fontnorm"><b><?php echo $lng['installation_successful']; ?></b></td></tr>
					<?php

					session_destroy();
				}
			}
		}

		?>
			</table>
			</body>
			</html>
		<?php
	break;
}

?>