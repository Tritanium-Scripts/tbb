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

	public function __construct() {
		define('INSTALLFILE','install.php');
	}

	public function executeMe() {
		$this->loadLanguage();

		session_start();
		session_name('sid');
		$MYSID = (SID == '') ? 'sid=0' : 'sid='.session_id();
		define('MYSID',$MYSID);

		$this->step = isset($_GET['step']) ? intval($_GET['step']) : 1;

		if($this->step < 1 || $this->step > 9)
			$this->step = 1;

		if(isset($_POST['buttonBack']) && $this->step != 1) {
			Functions::myHeader(INSTALLFILE.'?step='.($this->step-1).'&'.MYSID);
		}

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



		switch($this->step) {
			default:
				$p = Functions::getSGValues($_POST['p'],array('language'),'');

				if(isset($_GET['doit'])) {
					if(is_dir('languages/'.$p['language'])) {
						$_SESSION['language'] = $p['language'];
						Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
					}
				}

				$this->printHeader();

				?>
				<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="30%"/>
							<col width="70%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<tr>
							<td valign="top" class="CellWhite"><span class="FontNorm"><?php echo $this->strings['Select_language']; ?>:</span></td>
							<td valign="top" class="CellWhite">
								<select class="FormSelect" name="p[language]">
				<?php

				$dp = opendir('languages');
				while($curObj = readdir($dp)) {
					if($curObj[0] == '.' || !is_dir('languages/'.$curObj) || !file_exists('languages/'.$curObj.'/Language.config')) continue;

					$curLanguageConfig = parse_ini_file('languages/'.$curObj.'/Language.config');
					echo '<option value="'.$curObj.'"'.($curObj == $this->language ? ' selected="selected"' : '').'>'.$curLanguageConfig['language_name_native'].' ('.$curLanguageConfig['language_name'].')</option>';
				}
				closedir($dp);

				?>
								</select>
							</td>
						</tr>
						<tr><td class="CellButtons" align="right" colspan="2"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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
					Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
				}

				$this->printHeader();

				?>
				<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<tr><td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['introduction_text']; ?></span></td></tr>
						<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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
					if(!isset($_POST['buttonAgain'])) {
						Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
					}
				}

				$results = array(
					'filetest'=>array(
						'name'=>$this->strings['File_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'Red'
					),
					'fileuploadtest'=>array(
						'name'=>$this->strings['File_upload_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'Red'
					),
					'dirtest'=>array(
						'name'=>$this->strings['Directory_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'Red'
					),
					'phptest'=>array(
						'name'=>$this->strings['Php_test'],
						'success'=>FALSE,
						'error'=>$this->strings['successful'],
						'color'=>'Red'
					)
				);

				$this->printHeader();

				?>
				<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="20%"/>
							<col width="80%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
				<?php

				$_SESSION['disable_fupload'] = TRUE;
				$_SESSION['disable_aupload'] = TRUE;

				//
				// Zuerst wird geprueft, ob in die Configdatei geschrieben werden kann
				//
				if(!is_writable('config/DB.config.class.php')) $results['filetest']['error'] = $this->strings['error_cannot_write_config_file'];
				else {
					$results['filetest']['success'] = TRUE;
					$results['filetest']['color'] = 'Green';
				}

				//
				// Jetzt wird der Dateiupload uebperueft
				//
				if(@ini_get('file_uploads') != 1) {
					$results['fileuploadtest']['error'] = $this->strings['warning_file_upload_disabled'];
					$results['fileuploadtest']['color'] = 'Orange';
				}
				elseif(!is_writable('uploads/files')) {
					$results['fileuploadtest']['error'] = $this->strings['warning_file_upload_dir_not_writable'];
					$results['fileuploadtest']['color'] = 'Orange';
				}
				else {
					$results['fileuploadtest']['color'] = 'Green';
					$results['fileuploadtest']['success'] = TRUE;
					$_SESSION['disable_fupload'] = FALSE;
				}

				//
				// Jetzt wird ueberprueft, ob in die Verzeichnisse "upload/avatars" und "upload/files" geschrieben werden kann
				//
				$results['dirtest']['color'] = 'Orange';
				if(!is_writable('uploads/avatars')) $results['dirtest']['error'] = $this->strings['warning_avatar_upload_dir_not_writable'];
				elseif(!is_writable('cache')) $results['dirtest']['error'] = $this->strings['warning_cache_dir_not_writable'];
				else {
					$results['dirtest']['color'] = 'Green';
					$results['dirtest']['success'] = TRUE;
					$_SESSION['disable_aupload'] = FALSE;
				}

				//
				// Ueberpruefung der PHP-Version
				//
				$results['phptest']['color'] = 'Orange';
				if(phpversion() < 5) $results['phptest']['error'] = sprintf($this->strings['warning_old_php_version'],phpversion(),5);
				else {
					$results['phptest']['color'] = 'Green';
					$results['phptest']['success'] = TRUE;
				}

				$success = TRUE;
				foreach($results AS $curResult) {
					if(!$curResult['success'])
						$success = FALSE;

					$curResultText = '<span class="Font'.$curResult['color'].'">'.$curResult['error'].'</span>';

					?>
					<tr>
						 <td class="CellWhite" valign="top"><span class="FontNorm"><?php echo $curResult['name']; ?>:</span></td>
						 <td class="CellWhite" valign="top"><?php echo $curResultText; ?></td>
					</tr>
					<?php
				}

				if(!$success) {
					?>
					<tr><td class="CellError" colspan="2"><span class="FontError"><?php echo $this->strings['there_were_errors']; ?></span></td></tr>
					<?php
				}

				?>
						<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" name="buttonAgain" value="<?php echo $this->strings['Test_again']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
				 </form>
				<?php

				$this->printTail();
			break;



			//*
			//* Ueberprueft die MySQL-Verbindung
			//*
			case '4':
				$p = array();
				$p['dbServer'] = isset($_POST['p']['dbServer']) ? $_POST['p']['dbServer'] : (isset($_SESSION['dbserver']) ? $_SESSION['dbserver'] : 'localhost');
				$p['dbName'] = isset($_POST['p']['dbName']) ? $_POST['p']['dbName'] : (isset($_SESSION['dbname']) ? $_SESSION['dbname'] : '');
				$p['dbUser'] = isset($_POST['p']['dbUser']) ? $_POST['p']['dbUser'] : (isset($_SESSION['dbuser']) ? $_SESSION['dbuser'] : 'root');
				$p['dbPassword'] = isset($_POST['p']['dbPassword']) ? $_POST['p']['dbPassword'] : (isset($_SESSION['dbpassword']) ? $_SESSION['dbpassword'] : '');
				$p['tablePrefix'] = isset($_POST['p']['tablePrefix']) ? $_POST['p']['tablePrefix'] : (isset($_SESSION['tableprefix']) ? $_SESSION['tableprefix'] : 'tbb2_');

				$p['dbType'] = 'mysql';
				$errors = array();

				if(isset($_GET['doit'])) {
					if(trim($p['dbServer']) == '') $error = $this->strings['error_no_database_server'];
					if(trim($p['dbUser']) == '') $error = $this->strings['error_no_database_user'];
					if(trim($p['dbName']) == '') $error = $this->strings['error_no_database_name'];

					if(count($errors) == 0) {
						switch($p['dbType']) {
							case 'mysql':
								include_once('modules/DB/TSMySQL.class.php');
							break;
						}

						$this->DB = new TSMySQL;
						if(!$this->DB->connect($p['dbServer'],$p['dbUser'],$p['dbPassword'],$p['dbName'])) $errors[] = sprintf($this->strings['error_connecting_database_server'],$this->DB->getConnectError());
						if(!preg_match('/^[a-z0-9_]{0,}$/i',$p['tablePrefix'])) $errors[] = $this->strings['error_invalid_table_prefix'];

						if(count($errors) == 0) {
							$_SESSION['dbType'] = $p['dbType'];
							$_SESSION['dbServer'] = $p['dbServer'];
							$_SESSION['dbUser'] = $p['dbUser'];
							$_SESSION['dbName'] = $p['dbName'];
							$_SESSION['dbPassword'] = $p['dbPassword'];
							$_SESSION['tablePrefix'] = $p['tablePrefix'];

							Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
						}
					}
				}

				$this->printHeader();

				?>
				<form method="post" action="install.php?step=4&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="15%"/>
							<col width="85%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><span class="FontNorm"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></span></td></tr><?php } ?>
						<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['db_access_data_info']; ?></span></tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_server']; ?>:</b><span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbServer]" value="<?php echo $p['dbServer']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_user']; ?>:</b><span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbUser]" value="<?php echo $p['dbUser']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_password']; ?>:</b><span></td>
							<td class="CellWhite"><input class="FormText" type="password" name="p[dbPassword]" value="<?php echo $p['dbPassword']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_name']; ?>:</b><span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbName]" value="<?php echo $p['dbName']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Table_prefix']; ?>:</b><span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[tablePrefix]" value="<?php echo $p['tablePrefix']; ?>" size="10"/></td>
						</tr>
						<tr><td class="CellInfoBox" colspan="2"><span class="FontInfoBox"><?php echo $this->strings['search_for_installation_preinfo']; ?></span></td></tr>
						<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
				</form>
				<?php

				$this->printTail();
			break;


			case '5':
				switch($_SESSION['dbType']) {
					case 'mysql':
						include_once('modules/DB/TSMySQL.class.php');
					break;
				}

				$this->DB = new TSMySQL;
				$this->connect();

				$DATAVERSION = ''; // Beinhaltet spaeter die Version der Daten aus der Datenbank
				$_SESSION['drop_tables'] = FALSE; // Gibt spaeter an, ob die Tabellen vor der Installation geloescht werden
				$_SESSION['keep_data'] = FALSE;
				$existing_installation_text = $this->strings['existing_installation_not_found'];
				$select_options = array();

				$tablesData = array(); // Beinhaltet spaeter die Namen der einzelnen Tabellen
				$this->DB->query('SHOW TABLES'); // Die Namen der Tabellen aus der Datenbank holen
				$tablesData = $this->raw2Array(); // Die Daten in ein Array umwandeln

				foreach($tablesData  AS $curTable) {
					if($curTable[0] != $_SESSION['tablePrefix'].'config') continue;

					$this->DB->query('SELECT "configValue" FROM '.$_SESSION['tableprefix'].'config WHERE "configName"=\'dataversion\'');
					if($this->DB->numRows() != 0)
						list($DATAVERSION) = $this->DB->fetchArray();

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
									if(!file_exists('update/'.$NEXT_UPDATE_FILE)) die('Unknown Version!');
									$fp = fopen('update/'.$NEXT_UPDATE_FILE,'rb'); flock($fp,LOCK_SH);
									$toeval = fread($fp,filesize('update/'.$NEXT_UPDATE_FILE));
									flock($fp,LOCK_UN); fclose($fp);

									eval($toeval);
								} while($NEXT_UPDATE_FILE != '');


								$this->printHeader();

								?>
									<form method="post" action="install.php?step=5&amp;doit=1&amp;<?php echo $MYSID; ?>">
									<input type="hidden" name="p_action" value="2"/>
									<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
									<tr><td class="th1"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
									<tr><td><span class="FontNorm"><?php echo $this->strings['old_data_successfully_updated']; ?></td></tr>
									</table>
									<br/>
									<table border="0" cellpadding="2" cellspacing="0" width="100%" style="border:1px black dashed;">
									<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
									</table>
								<?php

								$this->printTail(); exit;
							}

							exit;
						}
					}

					if($DATAVERSION == '') $existing_installation_text = $this->strings['existing_installation_unknown'];
					elseif($DATAVERSION == SCRIPTVERSION) $existing_installation_text = $this->strings['existing_installation_good'];
					elseif($DATAVERSION < SCRIPTVERSION && file_exists('update/'.$DATAVERSION.'.update')) {
						$existing_installation_text = sprintf($this->strings['existing_installation_old_known'],$DATAVERSION);
						$select_options[] = array('3',$this->strings['Update_existing_data']);
					}
					elseif($DATAVERSION < SCRIPTVERSION) $existing_installation_text = sprintf($this->strings['existing_installation_old_unknown'],$DATAVERSION);
					elseif($DATAVERSION > SCRIPTVERSION) $existing_installation_text = $this->strings['existing_installation_newer'];

					$existing_installation_text = sprintf($this->strings['existing_installation_found'],$existing_installation_text);

					$select_options[] = array('2',$this->strings['Use_existing_data']);
					$select_options[] = array('1',$this->strings['Change_database_configuration']);
					$select_options[] = array('0',$this->strings['Delete_existing_data']);

					break;
				}

				if(isset($_GET['doit']))
					Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);

				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step+1; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
					<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td class="CellWhite"><span class="FontNorm"><?php echo $existing_installation_text; ?></td></tr>
				<?php
				if(count($select_options) > 0) {
					?>
						<tr>
						 <td class="CellWhite"><select name="p_action">
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
					<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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

				$this->DB = new db;
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
					<tr><td class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['basic_data_insertion_info']; ?></span></td></tr>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
				<?php

				if($_SESSION['drop_tables'] == TRUE) {
					?>
						<tr>
						  <td class="CellWhite" valign="top" width="20%"><span class="FontNorm"><?php echo $this->strings['Deleting_old_tables']; ?></span></td>
						  <td class="CellWhite" valign="top" width="80%"><span class="FontNorm">
					<?php

					$drop_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$drop_file);
					$this->DB->sql_split($drop_file);
					if($this->DB->execute_queries() == FALSE) echo '<span class="FontRed">'.$this->strings['error_deleting_old_tables'].'<br/><b>'.$this->DB->error().'</b></span>';
					else echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';

					?>
						</span></td>
						</tr>
					<?php
				}

				?>
					<tr>
					  <td class="CellWhite" valign="top" width="20%"><span class="FontNorm"><?php echo $this->strings['Creating_tables']; ?></span></td>
					  <td class="CellWhite" valign="top" width="80%"><span class="FontNorm">
				<?php

				$scheme_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$scheme_file);
				$this->DB->sql_split($scheme_file);
				if($this->DB->execute_queries() == FALSE) echo '<span class="FontRed">'.$this->strings['error_creating_tables'].'<br/><b>'.$this->DB->error().'</b></span>';
				else echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';

				?>
					 </span></td>
					 </tr>
					 <tr>
					  <td class="CellWhite" valign="top" width="20%"><span class="FontNorm"><?php echo $this->strings['Inserting_basic_data']; ?></span></td>
					  <td class="CellWhite" valign="top" width="80%"><span class="FontNorm">
				<?php

				$basic_file = str_replace('tblprefix.',$_SESSION['tableprefix'],$basic_file);
				$this->DB->sql_split($basic_file);
				if($this->DB->execute_queries() == FALSE) echo '<span class="FontRed">'.$this->strings['error_inserting_basic_data'].'<br/><b>'.$this->DB->error().'</b></span>';
				else {
					$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='".$_SESSION['language']."' WHERE config_name='standard_language'");
					$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='".SCRIPTVERSION."' WHERE config_name='dataversion'");
					echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';
				}

				?>
					  </span></td>
					 </tr>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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

				$this->DB = new db;
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
						$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_path_forum' WHERE config_name='path_to_forum'");
						$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_board_address' WHERE config_name='board_address'");
						$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_enable_file_upload' WHERE config_name='enable_file_upload'");
						$this->DB->query("UPDATE ".$_SESSION['tableprefix']."config SET config_value='$p_enable_avatar_upload' WHERE config_name='enable_avatar_upload'");

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
					<tr><td class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['board_configuration_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
					<tr>
					 <td width="40%"><span class="FontNorm"><b><?php echo $this->strings['Path_to_forum']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['path_to_forum_info']; ?></span></td>
					 <td width="60%"><input class="form_text" name="p_path_forum" value="<?php echo $p_path_forum; ?>" size="50"/></td>
					</tr>
					<tr>
					 <td width="40%"><span class="FontNorm"><b><?php echo $this->strings['Board_address']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['board_address_info']; ?></span></td>
					 <td width="60%"><input class="form_text" name="p_board_address" value="<?php echo $p_board_address; ?>" size="50"/></td>
					</tr>
					<tr>
					 <td width="40%"><span class="FontNorm"><b><?php echo $this->strings['Enable_file_upload']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['enable_file_upload_info']; ?></span></td>
					 <td width="60%"><select name="p_enable_file_upload"><option value="1"<?php echo $checked['enable_file_upload_1']; ?>><?php echo $this->strings['Yes']; ?></option><option value="0"<?php echo $checked['enable_file_upload_0']; ?>><?php echo $this->strings['No']; ?></option></select></td>
					</tr>
					<tr>
					 <td width="40%"><span class="FontNorm"><b><?php echo $this->strings['Enable_avatar_upload']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['enable_avatar_upload_info']; ?></span></td>
					 <td width="60%"><select name="p_enable_avatar_upload"><option value="1"<?php echo $checked['enable_avatar_upload_1']; ?>><?php echo $this->strings['Yes']; ?></option><option value="0"<?php echo $checked['enable_avatar_upload_0']; ?>><?php echo $this->strings['No']; ?></option></select></td>
					</tr>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td><span class="FontNorm">
				<?php

				if($_SESSION['keep_data'] == TRUE) {
					echo $this->strings['create_admin_keep_data_info'];
					?>
						<br/>
						<b><?php echo $this->strings['Create_another_admin']; ?>: </b> <select name="p_create_admin"><option value="0"<?php echo $checked['create_admin_0']; ?>><?php echo $this->strings['No']; ?></option><option value="1"<?php echo $checked['create_admin_1']; ?>><?php echo $this->strings['Yes']; ?></option></select>
					<?php
				}
				else echo $this->strings['create_admin_info'];

				?>
					</span></td></tr>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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

				$this->DB = new db;
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
						$this->DB->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_nick,user_email,user_pw,user_regtime) VALUES ('1','1','$p_user_name','$p_email_address','".mycrypt($p_password)."','".time()."')");
						header("Location: install.php?step=9&$MYSID"); exit;
					}

				}

				$this->printHeader();

				?>
					<form method="post" action="install.php?step=8&amp;doit=1&amp;<?php echo $MYSID; ?>">
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['administrator_creation_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
					<?php if($error != '') echo '<tr><td class="error" colspan="2"><span class="fonterror">'.$error.'</span></td></tr>'; ?>
					<tr>
					 <td width="25%"><span class="FontNorm"><b><?php echo $this->strings['User_name']; ?>:</b></span><br/><span class="FontSmall"><?php echo $this->strings['user_name_info']; ?></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_user_name" value="<?php echo $p_user_name; ?>" size="16" maxlength="15"/></td>
					</tr>
					<tr>
					 <td width="25%"><span class="FontNorm"><b><?php echo $this->strings['Email_address']; ?>:</b></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_email_address" value="<?php echo $p_email_address; ?>" size="30"/></td>
					</tr>
					<tr>
					 <td width="25%"><span class="FontNorm"><b><?php echo $this->strings['Email_address_confirmation']; ?>:</b></span></td>
					 <td width="75%"><input type="text" class="form_text" name="p_email_address_confirmation" value="<?php echo $p_email_address_confirmation; ?>" size="30"/></td>
					</tr>
					<tr>
					 <td width="25%"><span class="FontNorm"><b><?php echo $this->strings['Password']; ?>:</b></span></td>
					 <td width="75%"><input type="password" class="form_text" name="p_password" value="" size="20"/></td>
					</tr>
					<tr>
					 <td width="25%"><span class="FontNorm"><b><?php echo $this->strings['Password_confirmation']; ?>:</b></span></td>
					 <td width="75%"><input type="password" class="form_text" name="p_password_confirmation" value="" size="20"/></td>
					</tr>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
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

				$this->DB = new db;
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

							$message = (chmod('dbconfig.php',0775) == TRUE) ? '' : '<br/><b>'.$this->strings['Cannot_set_chmod'].'</b>';

							cache_set_all_data();

							$this->printHeader();

							?>
								<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
								<tr><td class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
								<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_successful'].$message; ?></span></td></tr>
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
					<tr><td class="th1" colspan="2"><span class="th1"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_finish_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
				<?php

				if(isset($_GET['doit'])) {
					?>
						<tr>
						 <td width="25%"><span class="FontNorm"><?php echo $this->strings['Creating_config_file']; ?></span></td>
						 <td width="75%" class="error"><span class="error"><?php echo $error; ?></span></td>
						</tr>
					<?php
				}

				?>
					</table>
					<br/>
					<table class="standard_table" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr><td align="right"><input class="form_button" type="submit" name="p_button_back" value="<?php echo $this->strings['Back']; ?>"/>&nbsp;&nbsp;&nbsp;<input class="form_bold_button" type="submit" name="p_button_next" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;
		}

	}

	function connect() {
		if(!$this->DB->connect($_SESSION['dbserver'],$_SESSION['dbuser'],$_SESSION['dbpassword'],$_SESSION['dbname'])) die(sprintf($this->strings['error_connecting_database_server'],$this->DB->getConnectError()));
		elseif(!preg_match('/^[a-z0-9_]{0,}$/i',$_SESSION['tableprefix'])) die($this->strings['error_invalid_table_prefix']);

		define('TBLPFX',$_SESSION['tableprefix']);

		return TRUE;
	}

	public function raw2Array() {
		$temp = array();
		while($curRow = $this->DB->fetchArray())
			$temp[] = $curRow;

		return $temp;
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

		$this->language = $languageCode;

		$this->parseLanguageFile('languages/'.$languageCode.'/Install.language');
		$this->parseLanguageFile('languages/'.$languageCode.'/Main.language');
	}

	protected function parseLanguageFile($languageFile) {
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
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $this->strings['html_direction']; ?>" lang="<?php echo $this->strings['html_language']; ?>" xml:lang="<?php echo $this->strings['html_language']; ?>">
			<head>
	 			<title><?php echo $this->strings['Tbb2_installation']; ?></title>
 				<style type="text/css">
  					body {
						background-color:#DCDCDC;
  					}

					table.TableStd {
						background-color:#FFFFFF;
						border:2px #000000 solid;
						border-spacing:1px;
						empty-cells:show;
						border-collapse:separate;
					}

					td.CellWhite {
						background-color:#FFFFFF;
						padding:3px;
					}

					.FontNorm {
						font-family:verdana,arial;
						color:#000000;
						font-size:10pt;
					}

					.FontRed {
						font-family:verdana,arial;
						color:#FF0000;
						font-size:10pt;
						font-weight:bold;
					}

					.FontGreen {
						font-family:verdana,arial;
						color:#008000;
						font-size:10pt;
						font-weight:bold;
					}

					.FontOrange {
						font-family:verdana,arial;
						color:#FFA500;
						font-size:10pt;
						font-weight:bold;
					}

					.FontGray {
						font-family:verdana,arial;
						color:#808080;
						font-size:10pt;
					}

					.FontSmall {
						font-family:verdana,arial;
						color:#000000;
						font-size:8pt;
					}

					.FontError {
						font-family:verdana;
						font-size:13px;
						color:#FF0000;
					}
					td.CellError{
						background-color:#FFD1D1;
						border:1px #FF0000 solid;
						padding:3px;
					}

					td.CellButtons {
						background-color:#778899;
						border:1px #000000 solid;
						padding:1px;
					}
					input.FormButton {
						background-color:#d4d0c8;
						border:2px #000000 outset;
						padding:2px;
						padding-left:15px;
						padding-right:15px;
						font-size:11px;
						font-family:verdana,arial;
					}
					input.FormButton:hover {
						background-color:#F8F8FF;
						border:2px #000000 inset;
						padding:2px;
						padding-left:15px;
						padding-right:15px;
						font-size:11px;
						font-family:verdana,arial;
						cursor:pointer;
					}
					input.FormBButton {
						background-color:#d4d0c8;
						border:2px #000000 outset;
						padding:2px;
						padding-left:15px;
						padding-right:15px;
						font-size:11px;
						font-family:verdana,arial;
						font-weight:bold;
					}
					input.FormBButton:hover {
						background-color:#F8F8FF;
						border:2px #000000 inset;
						padding:2px;
						padding-left:15px;
						padding-right:15px;
						font-size:11px;
						font-family:verdana,arial;
						font-weight:bold;
						cursor:pointer;
					}
					select.FormSelect {
						padding:2px;
						border:2px #000000 inset;
						font-size:11px;
						font-family:verdana,arial;
					}

					td.CellCat {
						padding:0px;
						padding-left:3px;
						padding-right:3px;
						height:25px;
						background-image:url(../images/cellcat.gif);
						background-color:#2c3fae;
						border:1px #000000 solid;
					}
					.FontCat {
						font-family:verdana,arial;
						font-size:13px;
						font-weight:bold;
						color:#FFFFFF;
					}

					td.CellStep {
						background-color:#FFFFFF;
						padding:5px;
						padding-top:8px;
						padding-bottom:8px;
					}
					td.CellStepActive {
						background-color:#a9b3d9;
						padding:4px;
						padding-top:7px;
						padding-bottom:7px;
						cursor:default;
						border:1px #838ba8 solid;
					}
					.FontStep {
						font-family:verdana,arial;
						font-size:11px;
						color:black;
						text-decoration:none;
					}

					a:link, a:visited {
						color:#0000CD;
					}
					a:hover {
						color:red;
					}

					input {
						border:1px black solid;
					}

					input.FormText {
						border:1px #000000 dotted;
						padding:3px;
						font-size:11px;
						font-family:verdana,arial;
					}
					input.FormText:hover {
						border:1px #696969 solid;
					}
					input.FormText:focus {
						border:1px #000000 solid;
						padding:3px;
						font-size:11px;
						font-family:verdana,arial;
					}

					td.CellError {
						background-color:#FFD1D1;
						border:1px #FF0000 solid;
					}
					.FontError {
						font-family:verdana;
						font-size:10pt;
						color:#FF0000;
					}

					td.CellInfoBox {
						background-color:#E6E6FA;
						border:1px #0000CD dotted;
						padding:4px;
						margin-top:3px;
						margin-bottom:3px;
						vertical-align:middle;
					}
					.FontInfoBox {
						font-family:verdana,arial;
						color:#111755;
						font-size:13px;
					}

					table.TableCopyright {
						border:1px #ACACAC solid;
						border-spacing:0px;
					}

					td.CellCopyright {
						background-color:#F0F8FF;
						padding:5px;
					}

					.FontCopyright {
						font-size:10px;
						color:#666699;
						font-family:verdana,arial;
						font-weight:bold;
					}
					a.FontCopyright:link, a.FontCopyright:visited, a.FontCopyright:active {
						color:#a62a2a;
					}
				</style>
			</head>
			<body>
				<div align="center"><div align="left" style="width:1024px;">
				<table style="background-color:#000000;" border="0" cellpadding="0" cellspacing="1" width="100%">
					<tr><td style="background-color:#000080; padding:5px; text-align:center;"><span style="color:#FFFFFF; font-size:24px; font-family:verdana,arial;"><?php echo $this->strings['Tbb2_installation']; ?></span></tr></td>
					<tr>
						<td class="CellWhite">
							<table border="0" cellpadding="3" cellspacing="5" width="100%">
								<tr>
									<td width="20%" valign="top">
										<table class="TableStd" width="100%"">
											<tr><td class="CellCat"><span class="FontCat">&Uuml;bersicht</span></td></tr>
											<?php

											foreach($this->steps AS $curKey => $curValue) {
												if($this->step-1 == $curKey)
													echo '<tr><td class="CellStepActive"><span class="FontStep"><b>&#187; '.$curValue.'</b></span></td></tr>';
												else
													echo '<tr><td class="CellStep"><span class="FontStep">'.$curValue.'</span></td></tr>';
											}

											?>
										</table>
									</td>
									<td width="80%" valign="top">
		<?php
	}

	protected function printTail() {
		?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<br/><br/><div align="center"><table class="TableCopyright"><tr><td class="CellCopyright" align="center"><span class="FontCopyright">Tritanium Bulletin Board 2 Installation<br/>&copy; 2003-2007 <a class="FontCopyright" href="http://www.tritanium-scripts.com" target="_blank">Tritanium Scripts</a></span></td></tr></table></div>
				</div></div>
			</body>
		</html>
		<?php
	}
}

?>