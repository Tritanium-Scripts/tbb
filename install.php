<?php

require_once('core/Functions.class.php');
require_once('core/Version.php');

$installation = new BoardInstall;
$installation->execute();

class BoardInstall {
	protected $strings = array();
	protected $steps = array();
	protected $language = NULL;
	protected $step = NULL;
	protected $DB = NULL;

	public function __construct() {
		define('INSTALLFILE','install.php');
	}

	public function execute() {
		$this->loadLanguage();

		session_start();
		session_name('sid');
		$mySID = (SID == '') ? 'sid=0' : 'sid='.session_id();
		define('MYSID',$mySID);

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

			/**
			 * Systemueberpruefung
			 */
			case '3':
				if(isset($_GET['doit']) && !isset($_POST['buttonAgain'])) {
					Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
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
				$p['tablePrefix'] = isset($_POST['p']['tablePrefix']) ? $_POST['p']['tablePrefix'] : (isset($_SESSION['tablePrefix']) ? $_SESSION['tablePrefix'] : 'tbb2_');

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
				<form method="post" action="install.php?step=4&amp;doit=1&amp;<?php echo $mySID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="15%"/>
							<col width="85%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></td></tr><?php } ?>
						<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['db_access_data_info']; ?></span></tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_server']; ?>:</b></span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbServer]" value="<?php echo $p['dbServer']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_user']; ?>:</b></span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbUser]" value="<?php echo $p['dbUser']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_password']; ?>:</b></span></td>
							<td class="CellWhite"><input class="FormText" type="password" name="p[dbPassword]" value="" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Database_name']; ?>:</b></span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[dbName]" value="<?php echo $p['dbName']; ?>" size="30"/></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><b><?php echo $this->strings['Table_prefix']; ?>:</b></span></td>
							<td class="CellWhite"><input class="FormText" type="text" name="p[tablePrefix]" value="<?php echo $p['tablePrefix']; ?>" size="10"/></td>
						</tr>
						<tr><td class="CellInfoBox" colspan="2"><span class="FontInfoBox"><?php echo $this->strings['search_for_installation_preinfo']; ?></span></td></tr>
						<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
				</form>
				<?php

				$this->printTail();
				break;

			/**
			 * Suche nach vorhandener Installation
			 */
			case '5':
				$this->selectDBAndConnect();

				$DATAVERSION = ''; // Beinhaltet spaeter die Version der Daten aus der Datenbank
				$_SESSION['dropTables'] = FALSE; // Gibt spaeter an, ob die Tabellen vor der Installation geloescht werden
				$_SESSION['keepData'] = FALSE;
				$existingInstallationText = $this->strings['existing_installation_not_found'];
				$selectOptions = array();

				$tablesData = $this->DB->getTablesData();

				foreach($tablesData  AS $curTable) {
					if($curTable != $_SESSION['tablePrefix'].'config') continue;

					$this->DB->query('SELECT "configValue" FROM '.$_SESSION['tablePrefix'].'config WHERE "configName"=\'dataversion\'');
					if($this->DB->numRows() != 0)
						list($DATAVERSION) = $this->DB->fetchArray();

					if($DATAVERSION == '') $existingInstallationText = $this->strings['existing_installation_unknown'];
					elseif($DATAVERSION == SCRIPTVERSION) $existingInstallationText = $this->strings['existing_installation_good'];
					elseif($DATAVERSION < SCRIPTVERSION) {
						if(file_exists('update/'.$DATAVERSION.'.update')) {
							$existingInstallationText = sprintf($this->strings['existing_installation_old_known'],$DATAVERSION);
							$selectOptions[] = array('3',$this->strings['Update_existing_data']);
						}
						else
							$existingInstallationText = sprintf($this->strings['existing_installation_old_unknown'],$DATAVERSION);
					}
					elseif($DATAVERSION > SCRIPTVERSION) $existingInstallationText = $this->strings['existing_installation_newer'];

					$existingInstallationText = sprintf($this->strings['existing_installation_found'],$existingInstallationText);

					$selectOptions[] = array('2',$this->strings['Use_existing_data']);
					$selectOptions[] = array('1',$this->strings['Change_database_configuration']);
					$selectOptions[] = array('0',$this->strings['Delete_existing_data']);

					break;
				}
				
				if(isset($_GET['doit'])) {
					if(!isset($_POST['p']['action'])) Functions::myHeader(INSTALLFILE.'?step=6'.MYSID);
					elseif($_POST['p_action'] == '0') {
						$_SESSION['dropTables'] = TRUE;
						Functions::myHeader(INSTALLFILE.'?step=6'.MYSID);
					}
					elseif($_POST['p_action'] == '1') {
						Functions::myHeader(INSTALLFILE.'?step=4'.MYSID);
					}
					elseif($_POST['p_action'] == '2') {
						$_SESSION['keepData'] = TRUE;
						Functions::myHeader(INSTALLFILE.'?step=7'.MYSID);
					}
					elseif($_POST['p_action'] == '3') {
						$nextUpdateFile = $DATAVERSION.'.update';

						do {
							if(!file_exists('update/'.$nextUpdateFile)) die('Unknown Version!');
							$toEval = file_get_contents('update/'.$nextUpdateFile);
							eval($toEval);
						} while($nextUpdateFile != '');


						$this->printHeader();

						?>
							<form method="post" action="<?php echo INSTALLFILE; ?>?step=7&amp;<?php echo MYSID; ?>">
								<table class="TableStd" width="100%">
									<tr><td class="CellCat"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
									<tr><td><span class="FontNorm"><?php echo $this->strings['old_data_successfully_updated']; ?></span></td></tr>
									<tr><td align="right"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
								</table>
							</form>
						<?php

						$this->printTail(); exit;
					}
				}
				
				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
					<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td class="CellWhite"><span class="FontNorm"><?php echo $existingInstallationText; ?></span></td></tr>
				<?php
				if(count($selectOptions) > 0) {
					?>
						<tr>
						 <td class="CellWhite"><select name="p_action">
					<?php

					foreach($selectOptions AS &$curOption) {
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
					<tr><td class="CellInfoBox"><span class="FontInfoBox"><?php echo $this->strings['No_way_back']; ?></span></td></tr>
					<tr><td class="CellButtons" align="right" colspan="2"><input class="FormButton" type="submit" value="<?php echo $this->strings['Back']; ?>" name="buttonBack"/>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
				<?php

				$this->printTail(); exit;
				break;


			//*
			//* Fuegt die Basisdaten ein
			//*
			case '6':
				$this->selectDBAndConnect();
				
				if(isset($_GET['doit']))
					Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);

				switch($_SESSION['dbType']) {
					case 'mysql':
						$sqlSchemeFile = file_get_contents('modules/DB/TSMySQL.scheme.sql');
						$sqlBasicFile = file_get_contents('modules/DB/TSMySQL.basic.sql');
						$sqlDropFile = file_get_contents('modules/DB/TSMySQL.drop.sql');
						break;
				}

				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="25%"/>
							<col width="75%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['basic_data_insertion_info']; ?></span></td></tr>
				<?php

				if(isset($_SESSION['dropTables']) && $_SESSION['dropTables']) {
					?>
					<tr>
						<td class="CellWhite" valign="top"><span class="FontNorm"><?php echo $this->strings['Deleting_old_tables']; ?></span></td>
						<td class="CellWhite" valign="top"><span class="FontNorm">
					<?php

					$queryError = '';
					$sqlDropFile = str_replace('/*TABLEPREFIX*/',$_SESSION['tablePrefix'],$sqlDropFile);
					$queries = $this->DB->splitQueries($sqlDropFile);
					foreach($queries AS &$curQuery) {
						if(!$this->DB->query($curQuery)) {
							$queryError = $this->DB->getError();
							break;
						}
					}
					
					if($queryError != '') echo '<span class="FontRed">'.$this->strings['error_deleting_old_tables'].'<br/><b>'.$queryError.'</b></span>';
					else echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';

					?>
						</span></td>
						</tr>
					<?php
				}

				?>
				<tr>
					<td class="CellWhite" valign="top"><span class="FontNorm"><?php echo $this->strings['Creating_tables']; ?></span></td>
					<td class="CellWhite" valign="top">
						<span class="FontNorm">
				<?php

				$queryError = '';
				$sqlSchemeFile = str_replace('/*TABLEPREFIX*/',$_SESSION['tablePrefix'],$sqlSchemeFile);
				$queries = $this->DB->splitQueries($sqlSchemeFile);
				foreach($queries AS &$curQuery) {
					if(!$this->DB->query($curQuery)) {
						$queryError = $this->DB->getError();
						break;
					}
				}
				
				if($queryError != '') echo '<span class="FontRed">'.$this->strings['error_creating_tables'].'<br/><b>'.$queryError.'</b></span>';
				else echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';

				?>
						</span>
					</td>
				</tr>
				<tr>
					<td class="CellWhite" valign="top"><span class="FontNorm"><?php echo $this->strings['Inserting_basic_data']; ?></span></td>
					<td class="CellWhite" valign="top"><span class="FontNorm">
				<?php

				$queryError = '';
				$sqlBasicFile = str_replace('/*TABLEPREFIX*/',$_SESSION['tablePrefix'],$sqlBasicFile);
				$queries = $this->DB->splitQueries($sqlBasicFile);
				foreach($queries AS &$curQuery) {
					if(!$this->DB->query($curQuery)) {
						$queryError = $this->DB->getError();
						echo $curQuery;
						break;
					}
				}
				if($queryError != '') echo '<span class="FontRed">'.$this->strings['error_inserting_basic_data'].'<br/><b>'.$queryError.'</b></span>';
				else {
					$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'standard_language\'',array($_SESSION['language']));
					$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'dataversion\'',array(SCRIPTVERSION));
					echo '<span class="FontGreen">'.$this->strings['successful'].'</span>';
				}

				?>
					  </span></td>
					 </tr>
					<tr><td align="right" class="CellButtons" colspan="2"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;

			/**
			 * Board configuration
			 */
			case '7':
				$this->selectDBAndConnect();

				$p['pathToForum'] = isset($_POST['p']['pathToForum']) ? $_POST['p']['pathToForum'] : substr($_SERVER['SCRIPT_FILENAME'],0,strlen($_SERVER['SCRIPT_FILENAME'])-12);
				$p['boardAddress'] = isset($_POST['p']['boardAddress']) ? $_POST['p']['boardAddress'] : '';
				$p['enableFileUpload'] = isset($_POST['p']['enableFileUpload']) ? $_POST['p']['enableFileUpload'] : ($_SESSION['disable_fupload'] ? 0 : 1);
				$p['enableAvatarUpload'] = isset($_POST['p']['enableAvatarUpload']) ? $_POST['p']['enableAvatarUpload'] : ($_SESSION['disable_aupload'] ? 0 : 1);
				$p['createAdministrator'] = isset($_POST['p']['createAdministrator']) ? $_POST['p']['createAdministrator'] : 0;

				$errors = array();

				if(isset($_GET['doit'])) {
					if(!file_exists($p['pathToForum'].'/install.php') || !file_exists($p['pathToForum'].'/modules/Auth.class.php') || !file_exists($p['pathToForum'].'/core/Version.php')) $errors[] = $this->strings['error_wrong_path'];
					
					if(count($errors) == 0) {
						$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'path_to_forum\'',array($p['pathToForum']));
						$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'board_address\'',array($p['boardAddress']));
						$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'enable_file_upload\'',array($p['enableFileUpload']));
						$this->DB->queryParams('UPDATE '.$_SESSION['tablePrefix'].'config SET "configValue"=$1 WHERE "configName"=\'enable_avatar_upload\'',array($p['enableAvatarUpload']));

						if($_SESSION['keepData'] && $p['createAdministrator'] != 1) Functions::myHeader(INSTALLFILE.'?step=9&'.MYSID);
						else Functions::myHeader(INSTALLFILE.'?step=8&'.MYSID);
					}
				}
				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=<? echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="30%"/>
							<col width="70%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></td></tr><?php } ?>
						<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['board_configuration_info']; ?></span></td></tr>
						<tr>
							<td><span class="FontNorm"><b><?php echo $this->strings['Path_to_forum']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['path_to_forum_info']; ?></span></td>
							<td><input class="FormText" name="p[pathToForum]" value="<?php echo $p['pathToForum']; ?>" size="50"/></td>
						</tr>
						<tr>
							<td><span class="FontNorm"><b><?php echo $this->strings['Board_address']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['board_address_info']; ?></span></td>
							<td><input class="FormText" name="p[boardAddress]" value="<?php echo $p['boardAddress']; ?>" size="50"/></td>
						</tr>
						<tr>
							<td><span class="FontNorm"><b><?php echo $this->strings['Enable_file_upload']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['enable_file_upload_info']; ?></span></td>
							<td><span class="FontNorm"><label><input type="radio" name="p[enableFileUpload]" value="1"<?php if($p['enableFileUpload'] == 1) echo 'checked="checked"'; ?>/> <?php echo $this->strings['Yes']; ?></label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[enableFileUpload]" value="0"<?php if($p['enableFileUpload'] == 0) echo 'checked="checked"'; ?>/> <?php echo $this->strings['No']; ?></label></span></td>
						</tr>
						<tr>
							<td><span class="FontNorm"><b><?php echo $this->strings['Enable_avatar_upload']; ?></b></span><br/><span class="FontSmall"><?php echo $this->strings['enable_avatar_upload_info']; ?></span></td>
							<td><span class="FontNorm"><label><input type="radio" name="p[enableAvatarUpload]" value="1"<?php if ($p['enableAvatarUpload'] == 1) echo 'checked="checked"'; ?>/> <?php echo $this->strings['Yes']; ?></label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[enableAvatarUpload]" value="0"<?php if($p['enableAvatarUpload'] == 0) echo 'checked="checked"'; ?>/> <?php echo $this->strings['No']; ?></label></span></td>
						</tr>
				<?php

				if($_SESSION['keepData']) {
					?>
						<tr>
							<td><span class="FontNorm"><b><?php echo $this->strings['create_admin_keep_data_info']; ?></b></span></td>
							<td><span class="FontNorm"><label><input type="radio" name="p[createAdministrator]" value="1"<?php if ($p['createAdministrator'] == 1) echo 'checked="checked"'; ?>/> <?php echo $this->strings['Yes']; ?></label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[createAdministrator]" value="0"<?php if($p['createAdministrator'] == 0) echo 'checked="checked"'; ?>/> <?php echo $this->strings['No']; ?></label></span></td>
						</tr>
					<?php
				}
				else {
					?>
						<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['create_admin_info']; ?></span></td></tr>
					<?php
				}

				?>
							<tr><td align="right" class="CellButtons" colspan="2"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
						</table>
					</form>
				<?php

				$this->printTail();
				break;

			case '8':
				$this->selectDBAndConnect();
				
				$p = Functions::getSGValues($_POST['p'],array('userName','userPassword','userPasswordConfirmation','userEmailAddress','userEmailAddressConfirmation'),'');

				$errors = array();

				if(isset($_GET['doit'])) {
					if(!Functions::verifyUserName($p['userName'])) $errors[] = $this->strings['error_invalid_user_name'];
					//elseif(!Functions::unifyUserName($p['userName'])) $errors[] = $this->strings['error_existing_user_name'];
					if(!Functions::verifyEmailAddress($p['userEmailAddress'])) $errors[] = $this->strings['error_invalid_email_address'];
					elseif($p['userEmailAddress'] != $p['userEmailAddressConfirmation']) $errors[] = $this->strings['error_email_addresses_no_match'];
					if(trim($p['userPassword']) == '') $errors[] = $this->strings['error_invalid_password'];
					elseif($p['userPassword'] != $p['userPasswordConfirmation']) $error = $this->strings['error_pws_no_match'];
					
					if(count($errors) == 0) {
						$userPasswordSalt = Functions::getRandomString(10);
						$userPasswordEncrypted = Functions::getSaltedHash($p['userPassword'],$userPasswordSalt); // Passwort fuer Datenbank verschluesseln
						
						$this->DB->queryParams('
							INSERT INTO
								'.TBLPFX.'users
							SET
								"userIsActivated"=1,
								"userIsAdmin"=1,
								"userNick"=$1,
								"userEmailAddress"=$2,
								"userPassword"=$3,
								"userPasswordSalt"=$4,
								"userRegistrationTimestamp"=$5,
								"userTimeZone"=$6
						',array(
							$p['userName'],
							$p['userEmailAddress'],
							$userPasswordEncrypted,
							$userPasswordSalt,
							time(),
							'gmt'
						));
						Functions::myHeader(INSTALLFILE.'?step=9&'.MYSID);
					}

				}

				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=8&amp;doit=1&amp;<?php echo MYSID; ?>">
						<table class="TableStd" width="100%">
							<colgroup>
								<col width="25%"/>
								<col width="75%"/>
							</colgroup>
							<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
							<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></td></tr><?php } ?>
							<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['administrator_creation_info']; ?></span></td></tr>
							<tr><td colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
							<tr>
								<td><span class="FontNorm"><?php echo $this->strings['User_name']; ?>:</span><br/><span class="FontSmall"><?php echo $this->strings['user_name_info']; ?></span></td>
								<td><input type="text" class="FormText" name="p[userName]" value="<?php echo $p['userName']; ?>" size="16" maxlength="15"/></td>
							</tr>
							<tr>
								<td><span class="FontNorm"><?php echo $this->strings['Email_address']; ?>:</span></td>
								<td><input type="text" class="FormText" name="p[userEmailAddress]" value="<?php echo $p['userEmailAddress']; ?>" size="30"/></td>
							</tr>
							<tr>
								<td><span class="FontNorm"><?php echo $this->strings['Email_address_confirmation']; ?>:</span></td>
								<td><input type="text" class="FormText" name="p[userEmailAddressConfirmation]" value="<?php echo $p['userEmailAddressConfirmation']; ?>" size="30"/></td>
							</tr>
							<tr>
								<td><span class="FontNorm"><?php echo $this->strings['Password']; ?>:</span></td>
								<td><input type="password" class="FormText" name="p[userPassword]" value="" size="20"/></td>
							</tr>
							<tr>
								<td><span class="FontNorm"><?php echo $this->strings['Password_confirmation']; ?>:</span></td>
								<td><input type="password" class="FormText" name="p[userPasswordConfirmation]" value="" size="20"/></td>
							</tr>
							<tr><td class="CellButtons" align="right" colspan="2"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
						</table>
					</form>
				<?php

				$this->printTail();
			break;

			case '9':
				$this->selectDBAndConnect();

				$errors = array();

				if(isset($_GET['doit'])) {
					$toWrite = "<?php\n\nclass DBConfig extends ConfigTemplate {\n\tprotected \$config = array(\n\t\t'dbType'=>'".$_SESSION['dbType']."',\n\t\t'dbServer'=>'".$_SESSION['dbServer']."',\n\t\t'dbUser'=>'".$_SESSION['dbUser']."',\n\t\t'dbPassword'=>'".$_SESSION['dbPassword']."',\n\t\t'dbName'=>'".$_SESSION['dbName']."',\n\t\t'tablePrefix'=>'".$_SESSION['tablePrefix']."'\n\t);\n}\n\n?>";
					
					if(!@file_put_contents('config/DB.config.class.php',$toWrite,LOCK_EX)) $errors[] = $this->strings['Cannot_open_config_file'];
					
					if(count($errors) == 0) {
						$message = (chmod('config/DB.config.class.php',0644) ? '' : '<br/><b>'.$this->strings['Cannot_set_chmod'].'</b>');

						$this->printHeader();

						?>
							<table class="TableStd" width="100%">
								<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
								<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_successful'].$message; ?></span></td></tr>
							</table>
						<?php

						$this->printTail();

						exit;
					}
				}


				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=9&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
					<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_finish_info']; ?></span></td></tr>
					<tr><td colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
				<?php

				if(isset($_GET['doit'])) {
					?>
						<tr>
						 <td width="25%"><span class="FontNorm"><?php echo $this->strings['Creating_config_file']; ?></span></td>
						 <td width="75%" class="error"><span class="error"><?php echo $errors[1]; ?></span></td>
						</tr>
					<?php
				}

				?>
					<tr><td class="CellButtons" colspan="2" align="right"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
					</table>
					</form>
				<?php

				$this->printTail();
			break;
		}
	}

	protected function selectDBAndConnect() {
		switch($_SESSION['dbType']) {
			case 'mysql':
				include_once('modules/DB/TSMySQL.class.php');
				$this->DB = new TSMySQL;
			break;
		}

		$this->connect();
	}

	protected function connect() {
		if(!$this->DB->connect($_SESSION['dbServer'],$_SESSION['dbUser'],$_SESSION['dbPassword'],$_SESSION['dbName'])) die(sprintf($this->strings['error_connecting_database_server'],$this->DB->getConnectError()));
		elseif(!preg_match('/^[a-z0-9_]{0,}$/i',$_SESSION['tablePrefix'])) die($this->strings['error_invalid_table_prefix']);

		define('TBLPFX',$_SESSION['tablePrefix']);

		return TRUE;
	}

	protected function raw2Array() {
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