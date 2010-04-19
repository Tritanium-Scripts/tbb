<?php
/**
*
* Tritanium Bulletin Board 2 - install.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('functions.php');

session_start();
session_name('sid');
$MYSID = 'sid='.session_id();


//
// Einige nuetzliche Funktionen
//
function install_print_pheader() {
	$temp = array();

	$temp[] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	$temp[] = '<html>';
	$temp[] = '<head>';
	$temp[] = ' <title>Tritanium Bulletin Board 2 - Installation</title>';
	$temp[] = ' <style type="text/css">';
	$temp[] = ' <!--';
	$temp[] = '  table.tbl {';
	$temp[] = '  background-color:#000000;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  td.tdwhite {';
	$temp[] = '   background-color:#FFFFFF;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  th.thwhite {';
	$temp[] = '   background-color:#FFFFFF;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '';
	$temp[] = '  td.tdred {';
	$temp[] = '   background-color:#FF0000;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  td.tdgreen {';
	$temp[] = '   background-color:#00FF00;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  td.tdyellow {';
	$temp[] = '   background-color:#FFFF00;';
	$temp[] = '  }';
	$temp[] = '  .fontnorm {';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '   color:#000000;';
	$temp[] = '   font-size:10pt;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  .fontred {';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '   color:#800000;';
	$temp[] = '   font-size:10pt;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  .fontgreen {';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '   color:#008000;';
	$temp[] = '   font-size:10pt;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  .fontyellow {';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '   color:#808000;';
	$temp[] = '   font-size:10pt;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  .fontsmall {';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '   color:#000000;';
	$temp[] = '   font-size:8pt;';
	$temp[] = '  }';
	$temp[] = '';
	$temp[] = '  input.formbutton {';
	$temp[] = '   border:1px #000000 solid;';
	$temp[] = '   font-size:10px;';
	$temp[] = '   font-family:verdana,arial;';
	$temp[] = '  }';
	$temp[] = ' -->';
	$temp[] = ' </style>';
	$temp[] = '</head>';
	$temp[] = '<body>';

	echo implode("\n",$temp);
}

function install_print_ptail() {
	$temp = array();

	$temp[] = '<br /><br /><div align="center"><table class="tbl" cellspacing="1" cellpadding="3"><tr><td class="tdwhite"><span class="fontsmall">Tritanium Bulletin Board 2 Installation<br />&copy; 2003-2004 <a href="http://www.tritanium-scripts.com" target="_blank">Tritanium Scripts</a></span></td></tr></table></div>';
	$temp[] = '</body>';
	$temp[] = '</html>';

	echo implode("\n",$temp);
}


//
// Und weiter gehts...
//
switch(@$_GET['step']) {
	default:
		$p_language = isset($_POST['p_language']) ? $_POST['p_language'] : '';

		if(isset($_GET['doit'])) {
			if(is_dir('language/'.$p_language) == TRUE) {
				$_SESSION['language'] = $p_language;
				header("Location: install.php?step=2&$MYSID"); exit;
			}
		}

		install_print_pheader();

		?>
			 <form method="post" action="install.php?step=1&amp;doit=1&amp;<?php echo $MYSID; ?>">
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 1 - Language selection</span></th></tr>
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
		<?php

		install_print_ptail();
	break;


	//*
	//* Ueberpruft das System
	//*
	case '2':
		include_once('language/'.$_SESSION['language'].'/lng_install.php');
		$results = array(
			'filetest'=>array(
				'name'=>$lng['File_test'],
				'success'=>FALSE,
				'error'=>$lng['successful'],
				'color'=>'red'
			),
			'fileuploadtest'=>array(
				'name'=>$lng['File_upload_test'],
				'success'=>FALSE,
				'error'=>$lng['successful'],
				'color'=>'red'
			)
		);

		install_print_pheader();

		?>
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 2 - System test</span></th></tr>
		<?php

		//
		// Zuerst wird geprueft, ob eine Datei erstellt werden kann
		//
		if(is_writable('dbconfig.php') == FALSE) $results['filetest']['error'] = $lng['error_cannot_write_config_file'];
		else {
			$results['filetest']['success'] = TRUE;
			$results['filetest']['color'] = 'green';
		}

		//
		// Jetzt wird der Dateiupload uebperueft
		//
		$results['fileuploadtest']['success'] = TRUE;
		if(@ini_get('file_uploads') != 1) {
			$results['fileuploadtest']['error'] = $lng['warning_file_upload_disabled'];
			$results['fileuploadtest']['color'] = 'yellow';
		}
		else {
			$results['fileuploadtest']['color'] = 'green';
		}

		$success = TRUE;
		while(list(,$akt_result) = each($results)) {
			if($akt_result['success'] == FALSE)
				$success = FALSE;

			$akt_result_text = '<span class="font'.$akt_result['color'].'">'.$akt_result['error'].'</span>';

			?>
				<tr>
				 <td class="tdwhite" width="20%"><span class="fontnorm"><?php echo $akt_result['name']; ?>:</span></td>
				 <td class="td<?php echo $akt_result['color']; ?>" width="80%"><?php echo $akt_result_text; ?></td>
				</tr>
			<?php
		}

		?>
			<tr><td colspan="2" class="tdwhite"><span class="fontnorm">
		<?php

		if($success != TRUE)
			echo $lng['there_were_errors']."<br /><br /><a href=\"install.php?step=2&amp;$MYSID\">".$lng['Try_again'].'</a>';
		else echo "<a href=\"install.php?step=3&amp;$MYSID\">".$lng['Next_step'].'</a>'

		?>
			</td></tr>
			</table>
		<?php

		install_print_ptail();
	break;



	//*
	//* Ueberprueft die MySQL-Verbindung
	//*
	case '3':
		include_once('language/'.$_SESSION['language'].'/lng_install.php');

		$p_dbserver = isset($_POST['p_dbserver']) ? $_POST['p_dbserver'] : 'localhost';
		$p_dbname = isset($_POST['p_dbname']) ? $_POST['p_dbname'] : '';
		$p_dbuser = isset($_POST['p_dbuser']) ? $_POST['p_dbuser'] : 'root';
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

					header("Location: install.php?step=4&$MYSID"); exit;
				}
			}
		}

		install_print_pheader();

		?>

			 <form method="post" action="install.php?step=3&amp;doit=1&amp;<?php echo $MYSID; ?>">
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 3</span></th></tr>
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
		<?php

		install_print_ptail();
	break;

	case '4':
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

		install_print_pheader();

		?>
			 <table class="tbl" style="background-color:black;" border="0" cellpadding="3" cellspacing="1" width="100%">
			 <tr><th colspan="2" class="thwhite"><span class="fontnorm">Tritanium Bulletin Board 2 - Installation - Step 4</span></th></tr>
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
		<?php

		install_print_ptail();
	break;
}

?>