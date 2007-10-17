<?php

require_once('core/Functions.class.php');
require_once('core/Version.php');

$installation = new BoardInstall;
$installation->executeMe();

class BoardInstall {
	protected $strings = array();
	protected $steps = array();
	protected $language = NULL;
	protected $step = NULL;
	protected $DB = NULL;

	public function executeMe() {
		$this->loadLanguage();

		session_start();
		session_name('sid');
		$MYSID = (SID == '') ? 'sid=0' : 'sid='.session_id();

		$this->step = isset($_GET['step']) ? $_GET['step'] : 1;

		$this->steps = array(
			$this->strings['Language_selection'],
			$this->strings['Introduction'],
			$this->strings['System_test'],
			$this->strings['Database_configuration'],
			$this->strings['Search_for_existing_installation'],
			$this->strings['Base_data_insertion'],
			$this->strings['Board_configuration'],
			$this->strings['Administrator_creation'],
			$this->strings['Installation_finish']
		);

		switch(@$_GET['step']) {
			default:
				$p_language = isset($_POST['p_language']) ? $_POST['p_language'] : '';

				if(isset($_GET['doit'])) {
					if(is_dir('languages/'.$p_language)) {
						$_SESSION['language'] = $p_language;
						header("Location: install.php?step=2&$MYSID"); exit;
					}
				}

				$this->printHeader();

				?>
					 <form method="post" action="install.php?step=1&amp;doit=1&amp;<?php echo $MYSID; ?>">
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					 <tr>
					  <td valign="top" width="25%" class="tdwhite"><span class="fontnorm"><?php echo $this->strings['Select_language']; ?>:</span></td>
					  <td valign="top" width="75%" class="tdwhite"><select name="p_language">
				<?php

				$dp = opendir('languages');
				while($akt_item = readdir($dp)) {
					if($akt_item[0] == '.' || !is_dir('languages/'.$akt_item) || !file_exists('languages/'.$akt_item.'/Language.config')) continue;

					$cur_lng_config = parse_ini_file('languages/'.$akt_item.'/Language.config');
					echo '<option value="'.$akt_item.'"'.($akt_item == $this->language ? ' selected="selected"' : '').'>'.$cur_lng_config['language_name_native'].' ('.$cur_lng_config['language_name'].')</option>';
				}
				closedir($dp);

				?>
					  </select></td>
					 </tr>
					 </table>
					 <br />
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><td align="right"><input class="form_bold_button" type="submit" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					 </table>
					 </form>
				<?php

				$this->printTail();
			break;


			//*
			//* Einleitung
			//*
			case '2':
				if(isset($_GET['doit'])) {
					if(isset($_POST['p_button_back']))
						header("Location: install.php?step=1&$MYSID");
					else
						header("Location: install.php?step=3&$MYSID");

					exit;
				}

				$this->printHeader();

				?>
					 <form method="post" action="install.php?step=2&amp;doit=1&amp;<?php echo $MYSID; ?>">
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					 <tr><td><span class="fontnorm"><?php echo $this->strings['introduction_text']; ?></span></td></tr>
					 </table>
					 <br />
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><td align="right"><input class="form_button" type="submit" value="<?php echo $this->strings['Back']; ?>" name="p_button_back" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					 </table>
					 </form>
				<?php

				$this->printTail();
			break;


			//*
			//* Ueberpruft das System
			//*
			case '3':
				if(isset($_GET['doit'])) {
					if(!isset($_POST['p_button_again'])) {
						if(isset($_POST['p_button_back']))
							header("Location: install.php?step=2&$MYSID");
						else
							header("Location: install.php?step=4&$MYSID");

						exit;
					}
				}

				$results = array(
					'filetest'=>array(
						'name'=>$this->strings['File_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'red'
					),
					'fileuploadtest'=>array(
						'name'=>$this->strings['File_upload_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'red'
					),
					'dirtest'=>array(
						'name'=>$this->strings['Directory_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'red'
					),
					'phptest'=>array(
						'name'=>$this->strings['Php_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'red'
					)
				);

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=3&amp;doit=1&amp;<?php echo $MYSID; ?>">
					 <table border="0" cellpadding="3" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
				<?php

				$_SESSION['disable_fupload'] = TRUE;
				$_SESSION['disable_aupload'] = TRUE;

				//
				// Zuerst wird geprueft, ob in die Configdatei geschrieben werden kann
				//
				if(is_writable('dbconfig.php') == FALSE) $results['filetest']['error'] = $this->strings['error_cannot_write_config_file'];
				else {
					$results['filetest']['success'] = TRUE;
					$results['filetest']['color'] = 'green';
				}

				//
				// Jetzt wird der Dateiupload uebperueft
				//
				if(@ini_get('file_uploads') != 1) {
					$results['fileuploadtest']['error'] = $this->strings['warning_file_upload_disabled'];
					$results['fileuploadtest']['color'] = 'orange';
				}
				elseif(is_writable('upload/files') == FALSE) {
					$results['fileuploadtest']['error'] = $this->strings['warning_file_upload_dir_not_writable'];
					$results['fileuploadtest']['color'] = 'orange';
				}
				else {
					$results['fileuploadtest']['color'] = 'green';
					$results['fileuploadtest']['success'] = TRUE;
					$_SESSION['disable_fupload'] = FALSE;
				}

				//
				// Jetzt wird ueberprueft, ob in die Verzeichnisse "upload/avatars" und "upload/files" geschrieben werden kann
				//
				$results['dirtest']['color'] = 'orange';
				if(is_writable('upload/avatars') == FALSE) $results['dirtest']['error'] = $this->strings['warning_avatar_upload_dir_not_writable'];
				elseif(is_writable('cache') == FALSE) $results['dirtest']['error'] = $this->strings['warning_cache_dir_not_writable'];
				else {
					$results['dirtest']['color'] = 'green';
					$results['dirtest']['success'] = TRUE;
					$_SESSION['disable_aupload'] = FALSE;
				}

				//
				// Ueberpruefung der PHP-Version
				//
				$results['phptest']['color'] = 'orange';
				if(phpversion() < '4.3.3') $results['phptest']['error'] = sprintf($this->strings['warning_old_php_version'],phpversion(),'4.3.3');
				else {
					$results['phptest']['color'] = 'green';
					$results['phptest']['success'] = TRUE;
				}

				$success = TRUE;
				while(list(,$akt_result) = each($results)) {
					if($akt_result['success'] == FALSE)
						$success = FALSE;

					$akt_result_text = '<span class="font'.$akt_result['color'].'">'.$akt_result['error'].'</span>';

					?>
						<tr>
						 <td width="20%"><span class="fontnorm"><?php echo $akt_result['name']; ?>:</span></td>
						 <td width="80%"><?php echo $akt_result_text; ?></td>
						</tr>
					<?php
				}
				?>
					</table>
				<?php

				if($success == FALSE) {
					?>
						<br />
						<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed">
						<tr><td><span class="fontnorm"><?php echo $this->strings['there_were_errors']; ?></span></td></tr>
						</table>
					<?php
				}

				?>
					<br />
					<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_again" value="<?php echo $this->strings['Test_again']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;



			//*
			//* Ueberprueft die MySQL-Verbindung
			//*
			case '4':
				$p_dbserver = isset($_POST['p_dbserver']) ? $_POST['p_dbserver'] : (isset($_SESSION['dbserver']) ? $_SESSION['dbserver'] : 'localhost');
				$p_dbname = isset($_POST['p_dbname']) ? $_POST['p_dbname'] : (isset($_SESSION['dbname']) ? $_SESSION['dbname'] : '');
				$p_dbuser = isset($_POST['p_dbuser']) ? $_POST['p_dbuser'] : (isset($_SESSION['dbuser']) ? $_SESSION['dbuser'] : 'root');
				$p_dbpassword = isset($_POST['p_dbpassword']) ? $_POST['p_dbpassword'] : (isset($_SESSION['dbpassword']) ? $_SESSION['dbpassword'] : '');
				$p_tableprefix = isset($_POST['p_tableprefix']) ? $_POST['p_tableprefix'] : (isset($_SESSION['tableprefix']) ? $_SESSION['tableprefix'] : 'tbb2_');

				$p_dbtype = 'mysql';
				$error = '';

				if(isset($_GET['doit'])) {
					if(isset($_POST['p_button_back'])) {
						header("Location: install.php?step=3&$MYSID"); exit;
					}

					if(trim($p_dbserver) == '') $error = $this->strings['error_no_database_server'];
					elseif(trim($p_dbuser) == '') $error = $this->strings['error_no_database_user'];
					elseif(trim($p_dbname) == '') $error = $this->strings['error_no_database_name'];
					else{
						switch($p_dbtype) {
							case 'mysql':
								include_once('db/mysql.class.php');
							break;
						}

						$DB = new db;
						if($DB->connect($p_dbserver,$p_dbuser,$p_dbpassword) == FALSE) $error = sprintf($this->strings['error_connecting_database_server'],$DB->error());
						elseif($DB->select_db($p_dbname) == FALSE) $error = sprintf($this->strings['error_invalid_unknown_database_name'],$DB->error());
						elseif(preg_match('/^[a-z0-9_]{0,}$/i',$p_tableprefix) == FALSE) $error = $this->strings['error_invalid_table_prefix'];
						else {
							$_SESSION['dbtype'] = $p_dbtype;
							$_SESSION['dbserver'] = $p_dbserver;
							$_SESSION['dbuser'] = $p_dbuser;
							$_SESSION['dbname'] = $p_dbname;
							$_SESSION['dbpassword'] = $p_dbpassword;
							$_SESSION['tableprefix'] = $p_tableprefix;

							header("Location: install.php?step=5&$MYSID"); exit;
						}
					}
				}

				$this->printHeader();

				?>
					 <form method="post" action="install.php?step=4&amp;doit=1&amp;<?php echo $MYSID; ?>">
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><th colspan="2" class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					 <tr><td class="tdwhite" colspan="2"><span class="fontnorm"><?php echo $this->strings['db_access_data_info']; ?></span></tr>
				<?php

				if($error != '') echo '<tr><td colspan="2" class="error"><span class="error"><b>'.$error.'</b></span></td></tr>';

				?>
					 <tr>
					  <td width="15%"><span class="fontnorm"><b><?php echo $this->strings['Database_server']; ?>:</b><span></td>
					  <td width="85%"><input type="text" name="p_dbserver" value="<?php echo $p_dbserver; ?>" size="30" /></td>
					 </tr>
					 <tr>
					  <td width="15%"><span class="fontnorm"><b><?php echo $this->strings['Database_user']; ?>:</b><span></td>
					  <td width="85%"><input type="text" name="p_dbuser" value="<?php echo $p_dbuser; ?>" size="30" /></td>
					 </tr>
					 <tr>
					  <td width="15%"><span class="fontnorm"><b><?php echo $this->strings['Database_password']; ?>:</b><span></td>
					  <td width="85%"><input type="password" name="p_dbpassword" value="<?php echo $p_dbpassword; ?>" size="30" /></td>
					 </tr>
					 <tr>
					  <td width="15%"><span class="fontnorm"><b><?php echo $this->strings['Database_name']; ?>:</b><span></td>
					  <td width="85%"><input type="text" name="p_dbname" value="<?php echo $p_dbname; ?>" size="30" /></td>
					 </tr>
					 <tr>
					  <td width="15%"><span class="fontnorm"><b><?php echo $this->strings['Table_prefix']; ?>:</b><span></td>
					  <td width="85%"><input type="text" name="p_tableprefix" value="<?php echo $p_tableprefix; ?>" size="10" /></td>
					 </tr>
					 </table>
					 <br />
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><td><span class="fontnorm"><?php echo $this->strings['search_for_installation_preinfo']; ?></span></td></tr>
					 </table>
					 <br />
					 <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					 <tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					 </table>
					 </form>
				<?php

				$this->printTail();
			break;


			case '5':
				switch($_SESSION['dbtype']) {
					case 'mysql':
						include_once('db/mysql.class.php');
					break;
				}

				$DB = new db;
				install_connect_db();

				$DATAVERSION = ''; // Beinhaltet spaeter die Version der Daten aus der Datenbank
				$SCRIPTVERSION = SCRIPTVERSION; // Die Version der Scripte
				$_SESSION['drop_tables'] = FALSE; // Gibt spaeter an, ob die Tabellen vor der Installation geloescht werden
				$_SESSION['keep_data'] = FALSE;
				$existing_installation_text = $this->strings['existing_installation_not_found'];
				$select_options = array();
				$tables_data = array(); // Beinhaltet spaeter die Namen der einzelnen Tabellen
				$DB->query("SHOW TABLES"); // Die Namen der Tabellen aus der Datenbank holen
				$tables_data = $DB->raw2array(); // Die Daten in ein Array umwandeln

				while(list(,$akt_table) = each($tables_data)) {
					if(preg_match('/^'.$_SESSION['tableprefix'].'.*$/si',$akt_table[0]) == TRUE) {
						$DB->query("SELECT config_value FROM ".$_SESSION['tableprefix']."config WHERE config_name='dataversion'");
						if($DB->affected_rows != 0)
							list($DATAVERSION) = $DB->fetch_array();

						if(isset($_GET['doit'])) {
							if(isset($_POST['p_button_back'])) {
								header("Location: install.php?step=4&$MYSID");
							}
							elseif(isset($_POST['p_action'])) {
								if($_POST['p_action'] == '0') {
									$_SESSION['drop_tables'] = TRUE;
									header("Location: install.php?step=6&$MYSID");
								}
								elseif($_POST['p_action'] == '1') {
									header("Location: install.php?step=4&$MYSID");
								}
								elseif($_POST['p_action'] == '2') {
									$_SESSION['keep_data'] = TRUE;
									header("Location: install.php?step=7&$MYSID");
								}
								elseif($_POST['p_action'] == '3') {
									$NEXT_UPDATE_FILE = $DATAVERSION.'.update';

									do {
										if(file_exists('update/'.$NEXT_UPDATE_FILE) != TRUE) die('Unknown Version!');
										$fp = fopen('update/'.$NEXT_UPDATE_FILE,'rb'); flock($fp,LOCK_SH);
										$toeval = fread($fp,filesize('update/'.$NEXT_UPDATE_FILE));
										flock($fp,LOCK_UN); fclose($fp);

										eval($toeval);
									} while($NEXT_UPDATE_FILE != '');


									$this->printHeader();

									?>
										<form method="post" action="install.php?step=5&amp;doit=1&amp;<?php echo $MYSID; ?>">
										<input type="hidden" name="p_action" value="2" />
										<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
										<tr><th class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
										<tr><td><span class="fontnorm"><?php echo $this->strings['old_data_successfully_updated']; ?></td></tr>
										</table>
										<br />
										<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
										<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
										</table>
									<?php

									$this->printTail(); exit;
								}

								exit;
							}
						}

						if($DATAVERSION == '') $existing_installation_text = $this->strings['existing_installation_unknown'];
						elseif($DATAVERSION == $SCRIPTVERSION) $existing_installation_text = $this->strings['existing_installation_good'];
						elseif($DATAVERSION < $SCRIPTVERSION && file_exists('update/'.$DATAVERSION.'.update')) {
							$existing_installation_text = sprintf($this->strings['existing_installation_old_known'],$DATAVERSION);
							$select_options[] = array('3',$this->strings['Update_existing_data']);
						}
						elseif($DATAVERSION < $SCRIPTVERSION) $existing_installation_text = sprintf($this->strings['existing_installation_old_unknown'],$DATAVERSION);
						elseif($DATAVERSION > $SCRIPTVERSION) $existing_installation_text = $this->strings['existing_installation_newer'];

						$existing_installation_text = sprintf($this->strings['existing_installation_found'],$existing_installation_text);

						$select_options[] = array('2',$this->strings['Use_existing_data']);
						$select_options[] = array('1',$this->strings['Change_database_configuration']);
						$select_options[] = array('0',$this->strings['Delete_existing_data']);

						break;
					}



				}

				if(isset($_GET['doit'])) {
					if(isset($_POST['p_button_back'])) header("Location: install.php?step=4&$MYSID");
					else header("Location: install.php?step=6&$MYSID");

					exit;
				}

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=5&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					<tr><th class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					<tr><td><span class="fontnorm"><?php echo $existing_installation_text; ?></td></tr>
				<?php
				if(count($select_options) > 0) {
					?>
						<tr>
						 <td><select name="p_action">
					<?php

					while(list(,$akt_option) = each($select_options)) {
						?>
							<option value="<?php echo $akt_option[0]; ?>"><?php echo $akt_option[1]; ?></option>
						<?php
					}
					?>
						 </select></td>
						</tr>
					<?php
				}

				?>
					</table>
					<br />
					<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
				<?php

				$this->printTail(); exit;
			break;


			//*
			//* Fuegt die Basisdaten ein
			//*
			case '6':
				switch($_SESSION['dbtype']) {
					case 'mysql':
						include_once('db/mysql.class.php');
						$scheme_file = @fread(@fopen('db/mysql.scheme.sql','rb'),@filesize('db/mysql.scheme.sql'));
						$basic_file = @fread(@fopen('db/mysql.basic.sql','rb'),@filesize('db/mysql.basic.sql'));
						$drop_file = @fread(@fopen('db/mysql.drop.sql','rb'),@filesize('db/mysql.drop.sql'));
					break;
				}

				$DB = new db;
				install_connect_db();

				if(isset($_GET['doit'])) {
					if(isset($_POST['p_button_back'])) header("Location: install.php?step=5&$MYSID");
					else header("Location: install.php?step=7&$MYSID");

					exit;
				}

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=6&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><th class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					<tr><td colspan="2"><span class="fontnorm"><?php echo $this->strings['basic_data_insertion_info']; ?></span></td></tr>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
				<?php

				if($_SESSION['drop_tables'] == TRUE) {
					?>
						<tr>
						  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $this->strings['Deleting_old_tables']; ?></span></td>
						  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
					<?php

					$drop_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$drop_file);
					$DB->sql_split($drop_file);
					if($DB->execute_queries() == FALSE) echo '<span class="fontred">'.$this->strings['error_deleting_old_tables'].'<br /><b>'.$DB->error().'</b></span>';
					else echo '<span class="fontgreen">'.$this->strings['successful'].'</span>';

					?>
						</span></td>
						</tr>
					<?php
				}

				?>
					<tr>
					  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $this->strings['Creating_tables']; ?></span></td>
					  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
				<?php

				$scheme_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$scheme_file);
				$DB->sql_split($scheme_file);
				if($DB->execute_queries() == FALSE) echo '<span class="fontred">'.$this->strings['error_creating_tables'].'<br /><b>'.$DB->error().'</b></span>';
				else echo '<span class="fontgreen">'.$this->strings['successful'].'</span>';

				?>
					 </span></td>
					 </tr>
					 <tr>
					  <td class="tdwhite" valign="top" width="20%"><span class="fontnorm"><?php echo $this->strings['Inserting_basic_data']; ?></span></td>
					  <td class="tdwhite" valign="top" width="80%"><span class="fontnorm">
				<?php

				$basic_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$basic_file);
				$DB->sql_split($basic_file);
				if($DB->execute_queries() == FALSE) echo '<span class="fontred">'.$this->strings['error_inserting_basic_data'].'<br /><b>'.$DB->error().'</b></span>';
				else {
					$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='".$_SESSION['language']."' WHERE config_name='standard_language'");
					$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='".SCRIPTVERSION."' WHERE config_name='dataversion'");
					echo '<span class="fontgreen">'.$this->strings['successful'].'</span>';
				}

				?>
					  </span></td>
					 </tr>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;

			case '7':
				switch($_SESSION['dbtype']) {
					case 'mysql':
						include_once('db/mysql.class.php');
					break;
				}

				$DB = new db;
				install_connect_db();

				$p_path_forum = isset($_POST['p_path_forum']) ? $_POST['p_path_forum'] : substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-12);
				$p_board_address = isset($_POST['p_board_address']) ? $_POST['p_board_address'] : '';
				$p_enable_file_upload = isset($_POST['p_enable_file_upload']) ? $_POST['p_enable_file_upload'] : (($_SESSION['disable_fupload'] == TRUE) ? 0 : 1);
				$p_enable_avatar_upload = isset($_POST['p_enable_avatar_upload']) ? $_POST['p_enable_avatar_upload'] : (($_SESSION['disable_aupload'] == TRUE) ? 0 : 1);
				$p_create_admin = isset($_POST['p_create_admin']) ? $_POST['p_create_admin'] : 0;

				$error = '';

				if(isset($_GET['doit'])) {
					if(file_exists($p_path_forum.'/install.php') == FALSE || file_exists($p_path_forum.'/auth.php') == FALSE || file_exists($p_path_forum.'/version.php') == FALSE) $error = $this->strings['error_wrong_path'];
					else {
						$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_path_forum' WHERE config_name='path_to_forum'");
						$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_board_address' WHERE config_name='board_address'");
						$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_enable_file_upload' WHERE config_name='enable_file_upload'");
						$DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_enable_avatar_upload' WHERE config_name='enable_avatar_upload'");

						if($_SESSION['keep_data'] == TRUE && $p_create_admin != 1) header("Location: install.php?step=9&$MYSID");
						else header("Location: install.php?step=8&$MYSID");

						exit;
					}
				}

				$c = ' selected="selected"';
				$checked = array(
					'enable_file_upload_0'=>($p_enable_file_upload == 0) ? $c : '',
					'enable_file_upload_1'=>($p_enable_file_upload == 1) ? $c : '',
					'enable_avatar_upload_0'=>($p_enable_avatar_upload == 0) ? $c : '',
					'enable_avatar_upload_1'=>($p_enable_avatar_upload == 1) ? $c : '',
					'create_admin_0'=>($p_create_admin == 0) ? $c : '',
					'create_admin_1'=>($p_create_admin == 1) ? $c : ''
				);

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=7&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><th class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					<tr><td colspan="2"><span class="fontnorm"><?php echo $this->strings['board_configuration_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="fontnorm">&nbsp;</span></td></tr>
					<tr>
					 <td width="40%"><span class="fontnorm"><b><?php echo $this->strings['Path_to_forum']; ?></b></span><br /><span class="fontsmall"><?php echo $this->strings['path_to_forum_info']; ?></span></td>
					 <td width="60%"><input class="form_text" name="p_path_forum" value="<?php echo $p_path_forum; ?>" size="50" /></td>
					</tr>
					<tr>
					 <td width="40%"><span class="fontnorm"><b><?php echo $this->strings['Board_address']; ?></b></span><br /><span class="fontsmall"><?php echo $this->strings['board_address_info']; ?></span></td>
					 <td width="60%"><input class="form_text" name="p_board_address" value="<?php echo $p_board_address; ?>" size="50" /></td>
					</tr>
					<tr>
					 <td width="40%"><span class="fontnorm"><b><?php echo $this->strings['Enable_file_upload']; ?></b></span><br /><span class="fontsmall"><?php echo $this->strings['enable_file_upload_info']; ?></span></td>
					 <td width="60%"><select name="p_enable_file_upload"><option value="1"<?php echo $checked['enable_file_upload_1']; ?>><?php echo $this->strings['Yes']; ?></option><option value="0"<?php echo $checked['enable_file_upload_0']; ?>><?php echo $this->strings['No']; ?></option></select></td>
					</tr>
					<tr>
					 <td width="40%"><span class="fontnorm"><b><?php echo $this->strings['Enable_avatar_upload']; ?></b></span><br /><span class="fontsmall"><?php echo $this->strings['enable_avatar_upload_info']; ?></span></td>
					 <td width="60%"><select name="p_enable_avatar_upload"><option value="1"<?php echo $checked['enable_avatar_upload_1']; ?>><?php echo $this->strings['Yes']; ?></option><option value="0"<?php echo $checked['enable_avatar_upload_0']; ?>><?php echo $this->strings['No']; ?></option></select></td>
					</tr>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td><span class="fontnorm">
				<?php

				if($_SESSION['keep_data'] == TRUE) {
					echo $this->strings['create_admin_keep_data_info'];
					?>
						<br />
						<b><?php echo $this->strings['Create_another_admin']; ?>: </b> <select name="p_create_admin"><option value="0"<?php echo $checked['create_admin_0']; ?>><?php echo $this->strings['No']; ?></option><option value="1"<?php echo $checked['create_admin_1']; ?>><?php echo $this->strings['Yes']; ?></option></select>
					<?php
				}
				else echo $this->strings['create_admin_info'];

				?>
					</span></td></tr>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;

			case '8':
				switch($_SESSION['dbtype']) {
					case 'mysql':
						include_once('db/mysql.class.php');
					break;
				}

				$DB = new db;
				install_connect_db();

				$p_user_name = isset($_POST['p_user_name']) ? $_POST['p_user_name'] : '';
				$p_password = isset($_POST['p_password']) ? $_POST['p_password'] : '';
				$p_password_confirmation = isset($_POST['p_password_confirmation']) ? $_POST['p_password_confirmation'] : '';
				$p_email_address = isset($_POST['p_email_address']) ? $_POST['p_email_address'] : '';
				$p_email_address_confirmation = isset($_POST['p_email_address_confirmation']) ? $_POST['p_email_address_confirmation'] : '';

				$error = '';

				if(isset($_GET['doit'])) {
					if(verify_nick($p_user_name) == FALSE) $error = $this->strings['error_invalid_user_name'];
					elseif(unify_nick($p_user_name) == FALSE) $error = $this->strings['error_existing_user_name'];
					elseif(verify_email($p_email_address) == FALSE) $error = $this->strings['error_invalid_email_address'];
					elseif($p_email_address != $p_email_address_confirmation) $error = $this->strings['error_email_addresses_no_match'];
					elseif(trim($p_password) == '') $error = $this->strings['error_invalid_password'];
					elseif($p_password != $p_password_confirmation) $error = $this->strings['error_pws_no_match'];
					else {
						$DB->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_nick,user_email,user_pw,user_regtime) VALUES ('1','1','$p_user_name','$p_email_address','".mycrypt($p_password)."','".time()."')");
						header("Location: install.php?step=9&$MYSID"); exit;
					}

				}

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=8&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><th class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					<tr><td colspan="2"><span class="fontnorm"><?php echo $this->strings['administrator_creation_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="fontnorm">&nbsp;</span></td></tr>
					<?php if($error != '') echo '<tr><td class="error" colspan="2"><span class="fonterror">'.$error.'</span></td></tr>'; ?>
					<tr>
					 <td width="25%"><span class="fontnorm"><b><?php echo $this->strings['User_name']; ?>:</b></span><br /><span class="fontsmall"><?php echo $this->strings['user_name_info']; ?></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_user_name" value="<?php echo $p_user_name; ?>" size="16" maxlength="15" /></td>
					</tr>
					<tr>
					 <td width="25%"><span class="fontnorm"><b><?php echo $this->strings['Email_address']; ?>:</b></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_email_address" value="<?php echo $p_email_address; ?>" size="30" /></td>
					</tr>
					<tr>
					 <td width="25%"><span class="fontnorm"><b><?php echo $this->strings['Email_address_confirmation']; ?>:</b></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_email_address_confirmation" value="<?php echo $p_email_address_confirmation; ?>" size="30" /></td>
					</tr>
					<tr>
					 <td width="25%"><span class="fontnorm"><b><?php echo $this->strings['Password']; ?>:</b></span></td>
					 <td width="75%"><input type="password" class="form_text" name="p_password" value="" size="20" /></td>
					</tr>
					<tr>
					 <td width="25%"><span class="fontnorm"><b><?php echo $this->strings['Password_confirmation']; ?>:</b></span></td>
					 <td width="75%"><input type="password" class="form_text" name="p_password_confirmation" value="" size="20" /></td>
					</tr>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;

			case '9':
				switch($_SESSION['dbtype']) {
					case 'mysql':
						include_once('db/mysql.class.php');
					break;
				}

				$DB = new db;
				install_connect_db();

				$error = '';

				if(isset($_GET['doit'])) {
					if(!$fp = @fopen('dbconfig.php','wb')) $error = $this->strings['Cannot_open_config_file'];
					else {
						flock($fp,LOCK_EX);
						if(!@fwrite($fp,"<?php\n\n/*\n*\n* Automatisch erstellt von TBB. Nicht aendern oder loeschen!\n*\n*/\n\n\$CONFIG['db_type'] = 'mysql';\n\n\$CONFIG['db_server'] = '".$_SESSION['dbserver']."';\n\$CONFIG['db_user'] = '".$_SESSION['dbuser']."';\n\$CONFIG['db_password'] = '".$_SESSION['dbpassword']."';\n\$CONFIG['db_name'] = '".$_SESSION['dbname']."';\n\ndefine('TBLPFX','".TBLPFX."');\n\n?>")) $error = $this->strings['Cannot_write_config_file'];
						else {
							flock($fp,LOCK_UN);
							fclose($fp);

							$message = (chmod('dbconfig.php',0775) == TRUE) ? '' : '<br /><b>'.$this->strings['Cannot_set_chmod'].'</b>';

							cache_set_all_data();

							$this->printHeader();

							?>
								<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
								<tr><th class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
								<tr><td colspan="2"><span class="fontnorm"><?php echo $this->strings['installation_successful'].$message; ?></span></td></tr>
								</table>
							<?php

							$this->printTail();

							exit;
						}
						flock($fp,LOCK_UN);
						fclose($fp);
					}
				}


				$this->printHeader();

				?>
					<form method="post" action="install.php?step=9&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><th class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></th></tr>
					<tr><td colspan="2"><span class="fontnorm"><?php echo $this->strings['installation_finish_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="fontnorm">&nbsp;</span></td></tr>
				<?php

				if(isset($_GET['doit'])) {
					?>
						<tr>
						 <td width="25%"><span class="fontnorm"><?php echo $this->strings['Creating_config_file']; ?></span></td>
						 <td width="75%" class="error"><span class="error"><?php echo $error; ?></span></td>
						</tr>
					<?php
				}

				?>
					</table>
					<br />
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>" />&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>" /></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;
		}

	}

	function connect() {
		if($DB->connect($_SESSION['dbserver'],$_SESSION['dbuser'],$_SESSION['dbpassword']) == FALSE) die(sprintf($this->strings['error_connecting_database_server'],$DB->error()));
		elseif($DB->select_db($_SESSION['dbname']) == FALSE) die(sprintf($this->strings['error_invalid_unknown_database_name'],$DB->error()));
		elseif(preg_match('/^[a-z0-9_]{0,}$/i',$_SESSION['tableprefix']) == FALSE) die($this->strings['error_invalid_table_prefix']);

		define('TBLPFX',$_SESSION['tableprefix']);

		return TRUE;
	}

	protected function loadLanguage() {
		$languageFile = NULL;
		$supportedLanguages = array();

		if(isset($_SESSION['language']))
			$languageCode = $_SESSION['language'];
		else {
			$dp = opendir('languages');
			while($curObj = readdir($dp)) {
				if($curObj[0] == '.') continue;
				$curConfigFile = parse_ini_file('languages/'.$curObj.'/Language.config');
				foreach(explode(',',$curConfigFile['supported_languages']) AS $curLanguage)
					$supportedLanguages[$curLanguage] = $curObj;
			}
			$bestLanguage = $this->chooseLang(array_keys($supportedLanguages));

			if($bestLanguage != '')
				$languageCode = $bestLanguage;
		}

		if(is_null($languageFile))
			$languageCode = 'de';

		$languageFile = 'languages/'.$languageCode.'/Install.language';
		$this->language = $languageCode;

		foreach(explode("\n",file_get_contents($languageFile)) AS $curLine) {
			preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*(.*)$/',rtrim($curLine),$matches);

			if(count($matches) == 3)
				$this->strings[$matches[1]] = $matches[2];
		}
	}

	protected function chooseLang($availableLangs) {
		$pref = array();
		foreach(explode(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $lang) {
			$lang = explode(';',$lang);
			$matches = NULL;

			if(count($lang) == 1 || !preg_match('/q=([0-9.]+)/i',trim($lang[1]),$matches)) $pref['1.'.rand(0,9999)] = strtolower($lang[0]);
			else $pref[$matches[1].rand(0,9999)] = strtolower($lang[0]);
		}
		krsort($pref);

		return array_shift(array_merge(array_intersect($pref, $availableLangs), $availableLangs));
	}

	protected function printHeader() {
		?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		 <title><?php echo $this->strings['Tbb2_installation']; ?></title>
		 <style type="text/css">
		 <!--
		  body {
		   background-color:#F0F8FF;
		  }

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

		  .fontred {
		   font-family:verdana,arial;
		   color:#FF0000;
		   font-size:10pt;
		   font-weight:bold;
		  }

		  .fontgreen {
		   font-family:verdana,arial;
		   color:#008000;
		   font-size:10pt;
		   font-weight:bold;
		  }

		  .fontorange {
		   font-family:verdana,arial;
		   color:#FFA500;
		   font-size:10pt;
		   font-weight:bold;
		  }

		  .fontgray {
		   font-family:verdana,arial;
		   color:#808080;
		   font-size:10pt;
		  }

		  .fontsmall {
		   font-family:verdana,arial;
		   color:#000000;
		   font-size:8pt;
		  }

		  input.formbutton {
		   border:1px #000000 solid;
		   font-size:10px;
		   font-family:verdana,arial;
		  }

		  a:link {
		   color:#0000CD;
		  }

		  a:visited {
		   color:#0000CD;
		  }

		  a:hover {
		   color:red;
		  }

		  input {
		   border:1px black solid;
		  }

		  th.th0 {
		   background-color:#000080;
		   padding:5px;
		  }
		  .th0 {
		   color:#FFFFFF;
		   font-size:14pt;
		   font-family:verdana,arial;
		  }

		  th.th1 {
		   background-color:#4682B4;
		  }
		  .th1 {
		   color:white;
		   font-size:10pt;
		   font-family:verdana;
		   font-weight:bold;
		  }

		  td.error {
		   background-color:#FFD1D1;
		   border:1px #FF0000 solid;
		  }
		  .error {
		   font-family:verdana;
		   font-size:10pt;
		   color:#FF0000;
		  }

		  input.form_bold_button {
		   font-family:verdana,arial;
		   font-size:8pt;
		   font-weight:bold;
		  }

		  input.form_button {
		   font-family:verdana,arial;
		   font-size:8pt;
		  }

		  table.standard_table {
			border:1px #000000 dashed;
		  }
		 -->
		 </style>
		</head>
		<body>
		<table style="background-color:#000000;" border="0" cellpadding="0" cellspacing="1" width="100%">
		<tr><th class="th0"><span class="th0"><?php echo $this->strings['Tbb2_installation']; ?></span></tr></th>
		<tr><td style="background-color:white;">
		<table border="0" cellpadding="3" cellspacing="5" width="100%">
		<tr>
		 <td width="20%" valign="top">
		  <table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
		  <tr><th class="th1"><span class="th1">&Uuml;bersicht</span></th></tr>
		<?php


			while(list($akt_key,$akt_value) = each($this->steps)) {
				if($this->step-1 == $akt_key)
					echo '	<tr><td><span class="fontnorm"><b>&#187; '.$akt_value.'</b></span></td></tr>';
				else
					echo '	<tr><td><span class="fontgray">'.$akt_value.'</span></td></tr>';
			}

			reset($this->steps);

		?>
		  </table>
		 </td>
		 <td width="80%" valign="top">
		<?php
	}

	protected function printTail() {
		?> </td>
		</tr>
		</table>
		</td></tr>
		</table>
		<br /><br /><div align="center"><table class="tbl" cellspacing="1" cellpadding="3"><tr><td class="tdwhite"><span class="fontsmall">Tritanium Bulletin Board 2 Installation<br />&copy; 2003-2005 <a href="http://www.tritanium-scripts.com" target="_blank">Tritanium Scripts</a></span></td></tr></table></div>
		</body>
		</html>
		<?php
	}
}
exit;


//
// Und weiter gehts...
//

?>