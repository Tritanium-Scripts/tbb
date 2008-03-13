<?php
/**
 * Installation script for Tritanium Bulletin Board 2
 * @author Julian Backes <julian@tritanium-scripts.com>
 */

include_once('core/Functions.class.php');
include_once('core/Version.php');
include_once('functions/FuncCats.class.php');

$installation = new BoardInstall;
$installation->execute();

class BoardInstall {
	protected $strings = array();
	protected $steps = array();
	protected $language = NULL;
	protected $step = NULL;
	protected $DB = NULL;
	protected $transferTBB1Data = FALSE;
	protected $keepExistingData = TRUE;
	protected $existingInstallationFound = FALSE;
	
	/**
	 * Path to a Tritanium Bulletin Board 1 installation
	 * 
	 * This variable is only used if the user wants to convert an old Tritanium Bulletin Board 1 
	 *
	 * @var string
	 */
	protected $pathToTBB1 = '';
	
	/**
	 * Holds all variables required by TBB1 conversion
	 *
	 * @var array
	 */
	protected $tbb1ConversionProperties = array(
		'lastPostID'=>1,
		'lastTopicID'=>1,
		'lastOptionID'=>1,
		'membersCounter'=>0,
		'membersCompleteCounter'=>0,
		'topicsCounter'=>0,
		'topicsCompleteCounter'=>0,
		'statusPre'=>0,
		'statusMembers'=>0,
		'statusTopics'=>0,
		'statusPost'=>0,
		'dbIcqID'=>0,
		'dbHomepageID'=>0
	);
	
	/**
	 * Determines how many files are proceeded per call by the TBB1 conversion script
	 * 
	 * This should be decreased if the server is too slow
	 */
	const FILES_PER_ROUND = 200;

	public function __construct() {
		if(get_magic_quotes_gpc() == 1) {
			$_POST = Functions::stripSlashes($_POST);
			$_GET = Functions::stripSlashes($_GET);
			$_COOKIE = Functions::stripSlashes($_COOKIE);
			$_REQUEST = Functions::stripSlashes($_REQUEST);
		}

		define('INSTALLFILE','install.php');
		session_start();
		session_name('sid');
		
		if(isset($_SESSION['transferTBB1Data']))
			$this->transferTBB1Data = $_SESSION['transferTBB1Data'];
		if(isset($_SESSION['keepExistingData']))
			$this->keepExistingData = $_SESSION['keepExistingData'];
		if(isset($_SESSION['existingInstallationFound']))
			$this->existingInstallationFound = $_SESSION['existingInstallationFound']; 
		if(isset($_SESSION['pathToTBB1']))
			$this->pathToTBB1 = $_SESSION['pathToTBB1'];

		foreach($this->tbb1ConversionProperties AS $key => $value) {
			if(isset($_SESSION[$key]))
				$this->tbb1ConversionProperties[$key] = $_SESSION[$key];
		}
	}
	
	public function __destruct() {
		$_SESSION['transferTBB1Data'] = $this->transferTBB1Data;
		$_SESSION['keepExistingData'] = $this->keepExistingData; 
		$_SESSION['existingInstallationFound'] = $this->existingInstallationFound;
		$_SESSION['pathToTBB1'] = $this->pathToTBB1;

		foreach($this->tbb1ConversionProperties AS $key => $value) {
			$_SESSION[$key] = $this->tbb1ConversionProperties[$key]; 
		}
	}

	protected function executeSqlFile($fileName) {
		$queryError = '';
		$fileContents = file_get_contents($fileName);
		$fileContents = Functions::str_replace('/*TABLEPREFIX*/',$_SESSION['tablePrefix'],$fileContents);
		$queries = $this->DB->splitQueries($fileContents);
		foreach($queries AS &$curQuery) {
			if(!$this->DB->query($curQuery)) {
				$queryError = $this->DB->getError();
				break;
			}
		}
		
		return $queryError;
	}
	
	protected function printHeader($autoLocation = '') {
		if($autoLocation != '') $autoLocation = '<meta http-equiv="refresh" content="0; URL='.$autoLocation.'" />';

		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $this->strings['html_direction']; ?>" lang="<?php echo $this->strings['html_language']; ?>" xml:lang="<?php echo $this->strings['html_language']; ?>">
			<head>
	 			<title><?php echo $this->strings['Tbb2_installation']; ?></title>
	 			<?php echo $autoLocation; ?>
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
				<table style="background-color:#000000; border:2px black solid;" border="0" cellpadding="0" cellspacing="1" width="100%">
					<tr><td style="background-color:#000080; padding:5px; text-align:center;"><span style="color:#FFFFFF; font-size:24px; font-family:verdana,arial;"><?php echo $this->strings['Tbb2_installation']; ?></span></td></tr>
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
	
	public function execute() {
		$this->loadLanguage();

		$mySID = (SID == '') ? 'sid=0' : 'sid='.session_id();
		define('MYSID',$mySID);

		$this->step = isset($_GET['step']) ? intval($_GET['step']) : 1;

		if($this->step < 1 || $this->step > 10)
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
			$this->strings['Tbb1_conversion'],
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
					if(trim($p['dbServer']) == '') $errors[] = $this->strings['error_no_database_server'];
					if(trim($p['dbUser']) == '') $errors[] = $this->strings['error_no_database_user'];
					if(trim($p['dbName']) == '') $errors[] = $this->strings['error_no_database_name'];

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
							
							$this->existingInstallationFound = FALSE;

							Functions::myHeader(INSTALLFILE.'?step='.($this->step+1).'&'.MYSID);
						}
					}
				}

				$this->printHeader();

				?>
				<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
						<colgroup>
							<col width="15%"/>
							<col width="85%"/>
						</colgroup>
						<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
						<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></td></tr><?php } ?>
						<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['db_access_data_info']; ?></span></td></tr>
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
				$existingInstallationText = $this->strings['existing_installation_not_found'];
				$selectOptions = array();

				$tablesData = $this->DB->getTablesData();

				foreach($tablesData  AS $curTable) {
					if($curTable != $_SESSION['tablePrefix'].'config') continue;

					$this->existingInstallationFound = TRUE;
					
					$columnsData = $this->DB->getColumnsData($_SESSION['tablePrefix'].'config');
					
					if(in_array('config_value',$columnsData))
						$this->DB->query('SELECT "config_value" FROM '.$_SESSION['tablePrefix'].'config WHERE "config_name"=\'dataversion\'');
					else
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
				
				if(!$this->existingInstallationFound) {
					$selectOptions[] = array('0',$this->strings['Dont_transfer_tbb1_data']);
					$selectOptions[] = array('1',$this->strings['Transfer_tbb1_data']);
				}
				
				if(isset($_GET['doit'])) {
					if(!isset($_POST['p_action'])) Functions::myHeader(INSTALLFILE.'?step=6'.MYSID);
					elseif(!$this->existingInstallationFound) {
						if($_POST['p_action'] == '0') $this->transferTBB1Data = FALSE;
						else $this->transferTBB1Data = TRUE;
						Functions::myHeader(INSTALLFILE.'?step=6'.MYSID);
					}
					else {
						if($_POST['p_action'] == '0') {
							$this->keepExistingData = FALSE;
							Functions::myHeader(INSTALLFILE.'?step=6'.MYSID);
						}
						elseif($_POST['p_action'] == '1') {
							Functions::myHeader(INSTALLFILE.'?step=4'.MYSID);
						}
						elseif($_POST['p_action'] == '2') {
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
						 <td class="CellWhite"><select class="FormSelect" name="p_action">
					<?php

					foreach($selectOptions AS &$curOption) {
						?>
							<option value="<?php echo $curOption[0]; ?>"><?php echo $curOption[1]; ?></option>
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
					</form>
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
						$sqlSchemeFile = 'modules/DB/TSMySQL.scheme.sql';
						$sqlBasicFile = 'modules/DB/TSMySQL.basic.sql';
						$sqlDropFile = 'modules/DB/TSMySQL.drop.sql';
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

				if($this->existingInstallationFound && !$this->keepExistingData) {
					?>
					<tr>
						<td class="CellWhite" valign="top"><span class="FontNorm"><?php echo $this->strings['Deleting_old_tables']; ?></span></td>
						<td class="CellWhite" valign="top"><span class="FontNorm">
					<?php

					$queryError = $this->executeSqlFile($sqlDropFile);
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

				$queryError = $this->executeSqlFile($sqlSchemeFile);
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

				$queryError = $this->executeSqlFile($sqlBasicFile);
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

				$p['pathToForum'] = isset($_POST['p']['pathToForum']) ? $_POST['p']['pathToForum'] : Functions::substr($_SERVER['SCRIPT_FILENAME'],0,Functions::strlen($_SERVER['SCRIPT_FILENAME'])-12);
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

						if(!$this->existingInstallationFound && $this->transferTBB1Data) Functions::myHeader(INSTALLFILE.'?step=9&'.MYSID);
						elseif($this->existingInstallationFound && $this->keepExistingData && $p['createAdministrator'] != 1) Functions::myHeader(INSTALLFILE.'?step=10&'.MYSID);
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

				if(!$this->existingInstallationFound && $this->transferTBB1Data) {
					?>
						<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['next_step_transfer_tbb1_data_info']; ?></span></td></tr>
					<?php
				}
				elseif($this->existingInstallationFound && $this->keepExistingData) {
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
						Functions::myHeader(INSTALLFILE.'?step=10&'.MYSID);
					}

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
				$this->executeTBB1Conversion();
				break; 

			case '10':
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
								<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_successful'].$message; ?></span></td></tr>
							</table>
						<?php

						$this->printTail();

						exit;
					}
				}


				$this->printHeader();

				?>
					<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;<?php echo MYSID; ?>">
					<table class="TableStd" width="100%">
					<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
					<tr><td class="CellWhite" colspan="2"><span class="FontNorm"><?php echo $this->strings['installation_finish_info']; ?></span></td></tr>
					<tr><td class="CellWhite" colspan="2"><span class="FontNorm">&nbsp;</span></td></tr>
				<?php

				if(isset($_GET['doit'])) {
					?>
						<tr>
						 <td class="CellWhite" width="25%"><span class="FontNorm"><?php echo $this->strings['Creating_config_file']; ?></span></td>
						 <td class="CellWhite" width="75%" class="error"><span class="error"><?php echo $errors[1]; ?></span></td>
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

	protected function executeTBB1Conversion() {
		$this->selectDBAndConnect();
		
		$errors = array();
		
		$p = Functions::getSGValues($_POST['p'],array('pathToTBB1'),'');
		
		if(isset($_GET['doit'])) {
			if($this->pathToTBB1 == '') {
				if(!file_exists($p['pathToTBB1'].'/foren') || !file_exists($p['pathToTBB1'].'/members') || !file_exists($p['pathToTBB1'].'/polls') || !file_exists($p['pathToTBB1'].'/vars')) $errors[] = $this->strings['error_no_tbb1_installation_found'];
				
				if(count($errors) == 0) {
					$this->pathToTBB1 = $p['pathToTBB1'];
					Functions::myHeader(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=1&'.MYSID);
				}
			} else {
				$subStep = isset($_GET['subStep']) ? intval($_GET['subStep']) : 1;
				
				switch($subStep) {
					case '1':
						$this->tbb1ConversionProperties['statusPre'] = 0;
						$this->tbb1ConversionProperties['statusMembers'] = 0;
						$this->tbb1ConversionProperties['statusTopics'] = 0;
						$this->tbb1ConversionProperties['statusPost'] = 0;
						$this->tbb1ConversionProperties['topicsCounter'] = 0;
						$this->tbb1ConversionProperties['membersCounter'] = 0;
						$this->tbb1ConversionProperties['membersCompleteCounter'] = 0;
						$this->tbb1ConversionProperties['topicsCompleteCounter'] = 0;
						
						// groups
						$groupIDs = array();
						$groupsData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/groups.var');
						$this->DB->query('DELETE FROM '.TBLPFX.'groups');
						$this->DB->query('DELETE FROM '.TBLPFX.'groups_members');

						foreach($groupsData AS $curGroup) {
							$curGroup = self::tbb1ConversionExplodeByTab($curGroup);
	
							$this->DB->queryParams('
								INSERT INTO '.TBLPFX.'groups SET
									"groupID"=$1,
									"groupName"=$2
							',array(
								intval($curGroup[0]),
								self::tbb1ConversionUnmutate(utf8_encode($curGroup[1]))
							));
							$groupIDs[] = intval($curGroup[0]);
	
							$curGroupMembers = ($curGroup[3] == '') ? array() : explode(',',$curGroup[3]);
	
							foreach($curGroupMembers AS &$curMember)
								if($this->tbb1ConversionUserExists($curMember))
									$this->DB->queryParams('
										INSERT INTO '.TBLPFX.'groups_members SET
											"groupID"=$1,
											"memberID"=$2
									',array(
										intval($curGroup[0]),
										intval($curMember)
									));
						}
	
						// forums
						$forumsData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/foren.var');

						$this->DB->query('DELETE FROM '.TBLPFX.'forums');
						$this->DB->query('DELETE FROM '.TBLPFX.'forums_auth');
	
						$curOrdnerID = 1;
	
						foreach($forumsData AS $curForum) {
							$curForum = self::tbb1ConversionExplodeByTab($curForum);
	
							$curForumAuth = explode(',',$curForum[10]);
							$curForumMods = ($curForum[11] == '') ? array() : explode(',',$curForum[11]);
							$curForumCodes = explode(',',$curForum[7]);
	
							$curForum[5]++;
	
							$curForumShowLatestPosts = ($curForumAuth[0] == 1 || $curForumAuth[6] == 1) ? 1 : 0;
	
							$this->DB->queryParams('
								INSERT INTO '.TBLPFX.'forums SET
									"forumID"=$1,
									"catID"=$2,
									"orderID"=$3,
									"forumName"=$4,
									"forumDescription"=$5,
									"forumTopicsCounter"=$6,
									"forumPostsCounter"=$7,
									"forumEnableBBCode"=$8,
									"forumEnableHtmlCode"=$9,
									"forumEnableSmilies"=$10,
									"forumShowLatestPosts"=$11,
									"authViewForumMembers"=$12,
									"authPostTopicMembers"=$13,
									"authPostReplyMembers"=$14,
									"authPostPollMembers"=$15,
									"authEditPostsMembers"=$16,
									"authViewForumGuests"=$17,
									"authPostTopicGuests"=$18,
									"authPostReplyGuests"=$19,
									"authPostPollGuests"=$20
							',array(
								$curForum[0],
								$curForum[5],
								$curOrdnerID++,
								self::tbb1ConversionUnmutate(utf8_encode($curForum[1])),
								self::tbb1ConversionUnmutate(utf8_encode($curForum[2])),
								$curForum[3],
								$curForum[4],
								$curForumCodes[0],
								$curForumCodes[1],
								1,
								$curForumShowLatestPosts,
								$curForumAuth[0],
								$curForumAuth[1],
								$curForumAuth[2],
								$curForumAuth[3],
								$curForumAuth[4],
								$curForumAuth[6],
								$curForumAuth[7],
								$curForumAuth[8],
								$curForumAuth[9]
							));
	
							foreach($curForumMods AS $curMod)
								$this->DB->queryParams('
									INSERT INTO '.TBLPFX.'forums_auth SET
										"forumID"=$1,
										"authType"=$2,
										"authID"=$3,
										"authViewForum"=$4,
										"authPostTopic"=$5,
										"authPostReply"=$6,
										"authPostPoll"=$7,
										"authEditPosts"=$8,
										"authIsMod"=$9
								',array(
									$curForum[0],
									0,
									$curMod,
									1,
									1,
									1,
									1,
									1,
									1
								));
	
							if($curForumRights = self::tbb1ConversionFileToArray($this->pathToTBB1.'/foren/'.$curForum[0].'-rights.xbb')) {
								foreach($curForumRights AS &$curRight) {
									$curRight = self::tbb1ConversionExplodeByTab($curRight);
									$curRightType = ($curRight[1] == 1) ? 0 : 1;
									if($curRightType == 0 && $this->tbb1ConversionUserExists($curRight[2]) || $curRightType == 1 && in_array($curRight[2],$groupIDs))
										$this->DB->queryParams('
											INSERT INTO '.TBLPFX.'forums_auth SET
												"forumID"=$1,
												"authType"=$2,
												"authID"=$3,
												"authViewForum"=$4,
												"authPostTopic"=$5,
												"authPostReply"=$6,
												"authPostPoll"=$7,
												"authEditPosts"=$8,
												"authIsMod"=$9
										',array(
											$curForum[0],
											$curRightType,
											$curRight[2],
											$curRight[3],
											$curRight[4],
											$curRight[5],
											$curRight[6],
											$curRight[7],
											0
										));
								}
							}
	
							$this->tbb1ConversionProperties['topicsCounter'] += file_get_contents($this->pathToTBB1.'/foren/'.$curForum[0].'-ltopic.xbb',LOCK_SH);
						}
	
						
						// categories	
						$catsData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/kg.var');
						$this->DB->query('DELETE FROM '.TBLPFX.'cats');
						$this->DB->queryParams('INSERT INTO '.TBLPFX.'cats SET
							"catID"=$1,
							"catL"=$2,
							"catR"=$3,
							"catName"=$4
						',array(
							1,
							1,
							2,
							'ROOT'
						));
	
						foreach($catsData AS &$curCat) {
							$curCat = self::tbb1ConversionExplodeByTab($curCat);
							$curCat[0]++;
	
							$newCatID = FuncCats::addCatData(1,$this->DB);
							$this->DB->queryParams('
								UPDATE
									'.TBLPFX.'cats
								SET
									"catID"=$1,
									"catName"=$2
								WHERE
									"catID"=$3
							',array(
								$curCat[0],
								self::tbb1ConversionUnmutate(utf8_encode($curCat[1])),
								$newCatID
							));
						}


						// ranks
						$ranksData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/rank.var');
						$this->DB->query('DELETE FROM '.TBLPFX.'ranks');
	
						foreach($ranksData AS &$curRank) {
							$curRank = self::tbb1ConversionExplodeByTab($curRank);
	
							$curRankGfx = array();
							for($i = 0; $i < $curRank[4]; $i++)
								$curRankGfx[] = 'images/rankpics/ystar.gif';
							$curRankGfx = implode(';',$curRankGfx);
	
							$this->DB->queryParams('
								INSERT INTO '.TBLPFX.'ranks SET
									"rankID"=$1,
									"rankType"=$2,
									"rankName"=$3,
									"rankGfx"=$4,
									"rankPosts"=$5
							',array(
								$curRank[0],
								0,
								self::tbb1ConversionUnmutate(utf8_encode($curRank[1])),
								$curRankGfx,
								$curRank[2],
							));
						}


						// smilies/topic pics
						$smiliesData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/smilies.var');
						$topicPicsData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/tsmilies.var');
						$this->DB->query('DELETE FROM '.TBLPFX.'smilies');
	
						foreach($topicPicsData AS $curTopicPic) {
							$curTopicPic = self::tbb1ConversionExplodeByTab($curTopicPic);
	
							$this->DB->queryParams('
								INSERT INTO '.TBLPFX.'smilies SET
									"smileyID"=$1,
									"smileyType"=$2,
									"smileyFileName"=$3
							',array(
								$curTopicPic[0],
								1,
								$curTopicPic[1]
							));
						}
	
						foreach($smiliesData AS $curSmiley) {
							$curSmiley = self::tbb1ConversionExplodeByTab($curSmiley);
	
							$this->DB->queryParams('INSERT INTO '.TBLPFX.'smilies SET
								"smileyType"=$1,
								"smileyFileName"=$2,
								"smileySynonym"=$3,
								"smileyStatus"=$4
							',array(
								0,
								$curSmiley[2],
								$curSmiley[1],
								1
							));
						}
	
	
						$this->DB->query('DELETE FROM '.TBLPFX.'users');
						$this->DB->query('DELETE FROM '.TBLPFX.'pms');
						$this->DB->query('DELETE FROM '.TBLPFX.'profile_fields');
						$this->DB->query('DELETE FROM '.TBLPFX.'profile_fields_data');
	
						$this->DB->queryParams('
							INSERT INTO '.TBLPFX.'profile_fields SET
								"fieldName"=$1,
								"fieldType"=$2,
								"fieldRegexVerification"=$3,
								"fieldData"=$4,
                                "fieldLink"=$5
						',array(
							$this->strings['ICQ'],
							0,
							'/^[0-9]{1,}\$/si',
							serialize(array()),
                            '%1$s <img src="http://status.icq.com/online.gif?icq=%1$s&amp;img=2" alt="ICQ"/>'
						));
						$this->tbb1ConversionProperties['dbIcqID'] = $this->DB->getInsertID();
	
						$this->DB->queryParams('
							INSERT INTO '.TBLPFX.'profile_fields SET
								"fieldName"=$1,
								"fieldType"=$2,
								"fieldData"=$3
						',array(
							$this->strings['Homepage'],
							0,
							serialize(array())
						));
						$this->tbb1ConversionProperties['dbHomepageID'] = $this->DB->getInsertID();
	
						$this->tbb1ConversionProperties['membersCounter'] = file_get_contents($this->pathToTBB1.'/vars/last_user_id.var',LOCK_SH);
						$this->tbb1ConversionProperties['statusPre'] = 100;
	
						$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=2&'.MYSID); exit;
						break;
						
					case '2':
						$currentUserID = isset($_GET['currentUserID']) ? intval($_GET['currentUserID']) : 1;
						$lastUserID = file_get_contents($this->pathToTBB1.'/vars/last_user_id.var') + 1;
						
						$filesCounter = 0;
						for($i = $currentUserID; $i < $lastUserID; $i++) {
							$this->tbb1ConversionProperties['membersCompleteCounter']++;
							if(!file_exists($this->pathToTBB1.'/members/'.$i.'.xbb')) continue;
	
							$curUserData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/members/'.$i.'.xbb');
	
							if(preg_match('/^[0-9]{1,}$/si',$curUserData[0])) {
								do {
									$curUserData[0] = '_'.$curUserData[0];
									$this->DB->queryParams('SELECT "userID" FROM '.TBLPFX.'users WHERE "userNick"=$1',array($curUserData[0]));
								} while($this->DB->numRows() > 0);
							}
	
							$curUserData[6] = self::tbb1ConversionConvertRegdate2Time($curUserData[6]);
							$curUserData[2] = md5(Functions::getRandomString(8));
							$curUserIsAdmin = ($curUserData[4] == 1) ? 1 : 0;
	
							$this->DB->queryParams('
								INSERT INTO '.TBLPFX.'users SET
									"userID"=$1,
									"userIsActivated"=$2,
									"userIsAdmin"=$3,
									"userNick"=$4,
									"userEmailAddress"=$5,
									"userPassword"=$6,
									"userPostsCounter"=$7,
									"userRegistrationTimestamp"=$8,
									"userSignature"=$9,
									"userTimeZone"=$10,
									"userAvatarAddress"=$11
							',array(
								$curUserData[1],
								1,
								$curUserIsAdmin,
								self::tbb1ConversionUnmutate(utf8_encode($curUserData[0])),
								self::tbb1ConversionUnmutate(utf8_encode($curUserData[3])),
								$curUserData[2],
								$curUserData[5],
								$curUserData[6],
								self::tbb1ConversionUnmutate(utf8_encode(self::tbb1ConversionBr2Nl($curUserData[7]))),
								'gmt',
								$curUserData[10]
								
							));
	
							// icq
							if($curUserData[13] != '') {
								$this->DB->queryParams('
									INSERT INTO '.TBLPFX.'profile_fields_data SET
										"fieldID"=$1,
										"userID"=$2,
										"fieldValue"=$3
								',array(
									$this->tbb1ConversionProperties['dbIcqID'],
									$curUserData[1],
									$curUserData[13]
								));
							}
							
							//homepage
							if($curUserData[9] != '') {
								$this->DB->queryParams('
									INSERT INTO '.TBLPFX.'profile_fields_data SET
										"fieldID"=$1,
										"userID"=$2,
										"fieldValue"=$3
								',array(
									$this->tbb1ConversionProperties['dbHomepageID'],
									$curUserData[1],
									$curUserData[9]
								));
							}
	
							if($curUserPMsData = self::tbb1ConversionFileToArray($this->pathToTBB1.'/members/'.$i.'.pm')) {
								foreach($curUserPMsData AS $curPM) {
									$curPM = self::tbb1ConversionExplodeByTab($curPM);
	
									if(count($curPM) < 5) continue;
	
									$curPMSendTimestamp = self::tbb1ConversionConvertDate2Time($curPM[4]);
									$curPMFromID = 0;
									$curPMGuestNick = '';
	
									if(!$this->tbb1ConversionUserExists($curPM[3]))
										$curPMGuestNick = $this->strings['Unknown_user'];
									else
										$curPMFromID = $curPM[3];
	
									$curPMIsRead = ($curPM[7] == 1) ? 0 : 1;
	
									$this->DB->queryParams('
										INSERT INTO '.TBLPFX.'pms SET
											"pmFromID"=$1,
											"pmToID"=$2,
											"pmSubject"=$3,
											"pmMessageText"=$4,
											"pmIsRead"=$5,
											"pmSendTimestamp"=$6,
											"pmEnableBBCode"=$7,
											"pmEnableSmilies"=$8,
											"pmGuestNick"=$9
									',array(
										$curPMFromID,
										$curUserData[1],
										self::tbb1ConversionUnmutate(utf8_encode($curPM[1])),
										self::tbb1ConversionUnmutate(utf8_encode(self::tbb1ConversionBr2Nl($curPM[2]))),
										$curPMIsRead,
										$curPMSendTimestamp,
										$curPM[5],
										$curPM[6],
										$curPMGuestNick
									));
								}
							}
	
	
							if(++$j * 2 >= self::FILES_PER_ROUND) {
								$this->tbb1ConversionProperties['statusMembers'] = round($this->tbb1ConversionProperties['membersCompleteCounter']/$this->tbb1ConversionProperties['membersCounter'],2)*100;
								$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=2&currentUserID='.($i+1).'&'.MYSID); exit;
							}
						}
	
						$this->tbb1ConversionProperties['statusMembers'] = 100;
	
						$this->DB->query('DELETE FROM '.TBLPFX.'posts');
						$this->DB->query('DELETE FROM '.TBLPFX.'topics');
						$this->DB->query('DELETE FROM '.TBLPFX.'topics_subscriptions');
						$this->DB->query('DELETE FROM '.TBLPFX.'polls');
						$this->DB->query('DELETE FROM '.TBLPFX.'polls_options');
	
						$this->tbb1ConversionProperties['lastPostID'] = 1;
						$this->tbb1ConversionProperties['lastTopicID'] = 1;
						$this->tbb1ConversionProperties['lastOptionID'] = 1;
	
						$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=3&'.MYSID); exit;
						break;
	
					case '3':
						$this->DB->query('SELECT "forumID" FROM '.TBLPFX.'forums ORDER BY "forumID" ASC');
						$forumIDs = $this->raw2FVArray();
						$forumsCounter = count($forumIDs);
	
						$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : $forumIDs[0];
	
						for($i = 0; $i < $forumsCounter; $i++) {
							if($forumIDs[$i] != $forumID) continue;
	
							$currentTopicID = isset($_GET['currentTopicID']) ? intval($_GET['currentTopicID']) : 1;
							$lastTopicID = file_get_contents($this->pathToTBB1.'/foren/'.$forumID.'-ltopic.xbb',LOCK_SH) + 1;
	
							$filesCounter = 1;
							for($j = $currentTopicID; $j < $lastTopicID; $j++) {
								$this->tbb1ConversionProperties['topicsCompleteCounter']++;
	
								if(!file_exists($this->pathToTBB1.'/foren/'.$forumID.'-'.$j.'.xbb')) continue;
	
								$curTopicData = $this->tbb1ConversionFileToArray($this->pathToTBB1.'/foren/'.$forumID.'-'.$j.'.xbb');
	
								$curTopicInfo = $this->tbb1ConversionExplodeByTab($curTopicData[0]);
	
								if(count($curTopicInfo) < 8) continue;
	
								$curTopicCount = count($curTopicData);
	
								$curTopicFirstPostID = $curTopicLastPostID = $curTopicPic = 0;
								$curTopicStatus = ($curTopicInfo[0] == 1) ? 0 : 1;
								$curTopicRepliesCounter = $curTopicCount-2;
								$curTopicID = $this->tbb1ConversionProperties['lastPostID']++;
								$curTopicTitle = $this->tbb1ConversionUnmutate(utf8_encode($curTopicInfo[1]));
								$curTopicPosterID = 0;
	
								$curTopicGuestNick = '';
								$curTopicPostTimestamp = 0;
	
	
								if(strncmp($curTopicInfo[2],'0',1) == 0)
									$curTopicGuestNick = substr($curTopicInfo[2],1,strlen($curTopicInfo[2]));
								elseif(!file_exists($this->pathToTBB1.'/members/'.$curTopicInfo[2].'.xbb'))
									$curTopicGuestNick = $this->strings['Unknown_user'];
								else
									$curTopicPosterID = $curTopicInfo[2];
	
								$this->DB->queryParams('
									INSERT INTO '.TBLPFX.'topics SET
										"topicID"=$1,
										"forumID"=$2,
										"posterID"=$3,
										"topicIsClosed"=$4,
										"topicRepliesCounter"=$5,
										"topicViewsCounter"=$6,
										"topicTitle"=$7,
										"topicGuestNick"=$8
								',array(
									$curTopicID,
									$forumID,
									$curTopicPosterID,
									$curTopicStatus,
									$curTopicRepliesCounter,
									$curTopicInfo[6],
									$curTopicTitle,
									$curTopicGuestNick
								));
	
								if($curTopicInfo[4] == 1 && $curTopicPosterID != 0) {
									$this->DB->queryParams('
										INSERT INTO '.TBLPFX.'topics_subscriptions SET
											"topicID"=$1,
											"userID"=$2
									',array(
										$curTopicID,
										$curTopicPosterID
									));
								}
	
								for($k = 1; $k < $curTopicCount; $k++) {
									$curPostData = $this->tbb1ConversionExplodeByTab($curTopicData[$k]);
	
									if(count($curPostData) < 10) continue;
	
									if(count($curPostData) > 13) {
										$x = 4;
	
										do {
											$curPostData[3] .= $curPostData[$x];
											unset($curPostData[$x++]);
										} while(count($curPostData) > 13);
	
										$temp = array();
	
										foreach($curPostData AS $curValue)
											$temp[] = $curValue;
	
										$curPostData = &$temp;
										unset($temp);
									}
	
									$curPostID = $this->tbb1ConversionProperties['lastPostID']++;
									$curPostTimestamp = $this->tbb1ConversionConvertDate2Time($curPostData[2]);
									$curPostGuestNick = '';
									$curPostPosterID = 0;
	
									if(strncmp($curPostData[1],'0',1) == 0)
										$curPostGuestNick = substr($curPostData[1],1,strlen($curPostData[1]));
									elseif(!file_exists($this->pathToTBB1.'/members/'.$curPostData[1].'.xbb'))
										$curPostGuestNick = $this->strings['Unknown_user'];
									else
										$curPostPosterID = $curPostData[1];
	
									if($k == 1) {
										$curTopicFirstPostID = $curPostID;
										$curTopicPic = $curPostData[6];
										$curPostTitle = $curTopicTitle;
										$curTopicPostTimestamp = $curPostTimestamp;
									}
									else {
										$curPostTitle = 'Re: '.$curTopicTitle;
									}
									if($k == $curTopicRepliesCounter+1) $curTopicLastPostID = $curPostID;
	
									$this->DB->queryParams('
										INSERT INTO '.TBLPFX.'posts SET
											"postID"=$1,
											"topicID"=$2,
											"forumID"=$3,
											"posterID"=$4,
											"postTimestamp"=$5,
											"postIP"=$6,
											"smileyID"=$7,
											"postEnableBBCode"=$8,
											"postEnableSmilies"=$9,
											"postEnableHtmlCode"=$10,
											"postShowSignature"=$11,
											"postGuestNick"=$12,
											"postTitle"=$13,
											"postText"=$14
									',array(
										$curPostID,
										$curTopicID,
										$forumID,
										$curPostPosterID,
										$curPostTimestamp,
										$curPostData[4],
                                        ($curPostData[6] > file_get_contents($this->pathToTBB1.'/vars/tsmiliess.var')) ? '1' : $curPostData[6], //In case of invalid tsmilie
										$curPostData[8],
										$curPostData[7],
										$curPostData[9],
										1,
										$curPostGuestNick,
										$curPostTitle,
										$this->tbb1ConversionUnmutate($this->tbb1ConversionBr2Nl(utf8_encode($curPostData[3])))
									));
								}
	
								$this->DB->queryParams('
									UPDATE
										'.TBLPFX.'topics
									SET
										"topicFirstPostID"=$1,
										"topicLastPostID"=$2,
										"smileyID"=$3,
										"topicPostTimestamp"=$4
									WHERE
										"topicID"=$5
								',array(
									$curTopicFirstPostID,
									$curTopicLastPostID,
									$curTopicPic,
									$curTopicPostTimestamp,
									$curTopicID
								));
	
	
								if($curTopicInfo[7] != '' && file_exists($this->pathToTBB1.'/polls/'.$curTopicInfo[7].'-1.xbb')) {
									$curPollData = $this->tbb1ConversionFileToArray($this->pathToTBB1.'/polls/'.$curTopicInfo[7].'-1.xbb');
									$curPollCount = count($curPollData);
									$curPollInfo = $this->tbb1ConversionExplodeByTab($curPollData[0]);
	
									$curPollGuestNick = '';
									$curPollPosterID = 0;
									$curPollStartTimestamp = $this->tbb1ConversionConvertDate2Time($curPollInfo[2]);
									$curPollEndTimestamp = $curPollStartTimestamp + 604800;
	
									if($curPollInfo[1] == 0 || !file_exists($this->pathToTBB1.'/members/'.$curPollInfo[1].'.xbb'))
										$curPollGuestNick = $this->strings['Unknown_user'];
									else
										$curPollPosterID = $curPollInfo[1];
	
									$this->DB->queryParams('
										INSERT INTO '.TBLPFX.'polls SET
											"topicID"=$1,
											"posterID"=$2,
											"pollTitle"=$3,
											"pollVotesCounter"=$4,
											"pollGuestNick"=$5,
											"pollStartTimestamp"=$6,
											"pollEndTimestamp"=$7
									',array(
										$curTopicID,
										$curPollPosterID,
										$this->tbb1ConversionUnmutate(utf8_encode($curPollInfo[3])),
										$curPollInfo[4],
										$curPollGuestNick,
										$curPollStartTimestamp,
										$curPollEndTimestamp
									));
									$newPollID = $this->DB->getInsertID();
									
									$this->DB->queryParams('
										UPDATE
											'.TBLPFX.'topics
										SET
											"topicHasPoll"=$1
										WHERE
											"topicID"=$2
									',array(
										1,
										$curTopicID
									));
	
									for($k = 1; $k < $curPollCount; $k++) {
										$curOptionData = $this->tbb1ConversionExplodeByTab($curPollData[$k]);
										$curOptionID = $this->tbb1ConversionProperties['lastOptionID']++;
										$this->DB->queryParams('
											INSERT INTO '.TBLPFX.'polls_options SET
												"optionID"=$1,
												"pollID"=$2,
												"optionTitle"=$3,
												"optionVotesCounter"=$4
										',array(
											$curOptionID,
											$newPollID,
											$this->tbb1ConversionUnmutate(utf8_encode($curOptionData[1])),
											$curOptionData[2]
										));
									}
	
									$curPollVotes = file_get_contents($this->pathToTBB1.'/polls/'.$curTopicInfo[7].'-2.xbb',LOCK_SH);
									if($curPollVotes != '') {
										$curPollVotes = explode(',',$curPollVotes);
										foreach($curPollVotes AS $curPollVote) {
											if(file_exists($this->pathToTBB1.'/members/'.$curPollVote.'.xbb')) {
												$this->DB->queryParams('
													INSERT INTO '.TBLPFX.'polls_votes SET
														"pollID"=$1,
														"voterID"=$2
												',array(
													$newPollID,
													$curPollVote
												));
											}
										}
									}
	
									$filesCounter += 2;
								}
	
	
								if($filesCounter++ >= self::FILES_PER_ROUND) {
									$this->tbb1ConversionProperties['statusTopics'] = round($this->tbb1ConversionProperties['topicsCompleteCounter']/$this->tbb1ConversionProperties['topicsCounter'],2)*100;
									$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=3&forumID='.$forumID.'&currentTopicID='.($j+1).'&'.MYSID); exit;
								}
							}
	
							if($i != $forumsCounter - 1) {
								$this->tbb1ConversionProperties['statusTopics'] = round($this->tbb1ConversionProperties['topicsCompleteCounter']/$this->tbb1ConversionProperties['topicsCounter'],2)*100;
								$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=3&forumID='.$forumIDs[$i+1].'&'.MYSID); exit;
							}
						}
	
						$this->tbb1ConversionProperties['statusTopics'] = 100;
	
						$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=4&'.MYSID); exit;
						break;
	
					case '4':
						$this->DB->query('
							UPDATE
								'.TBLPFX.'forums t1
							LEFT JOIN (
								SELECT
									"forumID",
									MAX("postID") AS "postID"
								FROM
									'.TBLPFX.'posts
								GROUP BY
									"forumID"
							) t2 ON t1."forumID"=t2."forumID"
							SET
								t1."forumLastPostID"=t2."postID"
						');
	
						$newConfigData = array();

						$this->DB->query('SELECT "userID","userNick" FROM '.TBLPFX.'users ORDER BY "userID" DESC LIMIT 1'); 
						$newestUserData = $this->DB->fetchArray();
						$newConfigData[] = array($newestUserData['userID'],'newest_user_id');
						$newConfigData[] = array($newestUserData['userNick'],'newest_user_nick');
						
						$this->DB->query('SELECT COUNT(*) FROM '.TBLPFX.'users');
						list($usersCounter) = $this->DB->fetchArray();
						$newConfigData[] = array($usersCounter,'usersCounter');
						
						$settingsFile = self::tbb1ConversionFileToArray($this->pathToTBB1.'/vars/settings.var');
						$newConfigData[] = array($settingsFile[5],'board_name');
						$newConfigData[] = array(($settingsFile[25] == 1 ? 0 : 1),'guests_enter_board');
						$newConfigData[] = array($settingsFile[12],'enable_registration');
						$newConfigData[] = array($settingsFile[47],'avatar_image_height');
						$newConfigData[] = array($settingsFile[48],'avatar_image_width');
                        $newConfigData[] = array($settingsFile[4],'board_email_address');
                        $newConfigData[] = array($settingsFile[6],'board_logo');
                        $newConfigData[] = array($settingsFile[13],'maximum_registrations');
                        $newConfigData[] = array($settingsFile[14],'verify_email_address'); //create_reg_pw
                        $newConfigData[] = array($settingsFile[16],'topics_per_page');
                        $newConfigData[] = array($settingsFile[17],'posts_per_page');
                        $newConfigData[] = array($settingsFile[18],'wio_timeout');
                        $newConfigData[] = array($settingsFile[19],'enable_wio');
                        $newConfigData[] = array($settingsFile[21],'show_boardstats_forumindex');
                        $newConfigData[] = array(($settingsFile[22] > 0 ? 1 : 0),'show_latest_posts_forumindex');
                        $newConfigData[] = array($settingsFile[43],'enable_gzip');
                        $newConfigData[] = array($settingsFile[51],'enable_email_functions');
						
						foreach($newConfigData AS $curConfig) {
							$this->DB->queryParams('
								UPDATE
									'.TBLPFX.'config
								SET
									"configValue"=$1
								WHERE
									"configName"=$2
							',array(
								$curConfig[0],
								$curConfig[1]
							));
						}							

						//$this->DB->query("DELETE FROM ".TBLPFX."config WHERE config_name='dataversion'");
						//$this->DB->query("INSERT INTO ".TBLPFX."config (config_name,config_value) VALUES ('dataversion','".SCRIPTVERSION."')");
	
						$this->tbb1ConversionProperties['statusPost'] = 100;

						$this->tbb1ConversionPrintConversionStatus(INSTALLFILE.'?step='.$this->step.'&doit=1&subStep=5&'.MYSID); exit;
						break;
						
					case '5':
						if(isset($_GET['subDoit'])) {
							Functions::myHeader(INSTALLFILE.'?step=10&'.MYSID);
						}

						$this->printHeader();
						
						?>
						<form method="post" action="<?php echo INSTALLFILE; ?>?step=<?php echo $this->step; ?>&amp;doit=1&amp;subStep=5&amp;subDoit=1&amp;<?php echo MYSID; ?>">
							<table class="TableStd" width="100%">
								<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
								<tr><td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['tbb1_conversion_finished_info']; ?></span></td></tr>
								<tr><td class="CellButtons" align="right"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
							</table>
						</form>
						<?php
						
						$this->printTail(); exit;
						break;
				}
			}
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
				<?php if(count($errors) > 0) { ?> <tr><td colspan="2" class="CellError"><ul><?php foreach($errors AS $curError) echo '<li><span class="FontError">'.$curError.'</span></li>'; ?></ul></td></tr><?php } ?>
				<tr><td colspan="2"><span class="FontNorm"><?php echo $this->strings['tbb1_conversion_info']; ?></span></td></tr>
				<tr>
					<td><span class="FontNorm"><?php echo $this->strings['Path_to_tbb1']; ?>:</span><br/><span class="FontSmall"><?php echo $this->strings['path_to_tbb1_info']; ?></span></td>
					<td><input type="text" class="FormText" name="p[pathToTBB1]" value="<?php echo $p['pathToTBB1']; ?>" size="50"/></td>
				</tr>
				<tr><td class="CellButtons" align="right" colspan="2"><input class="FormBButton" type="submit" value="<?php echo $this->strings['Next']; ?>"/></td></tr>
			</table>
		</form>
		<?php
		
		$this->printTail();
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
	
	protected function raw2FVArray() {
		$temp = array();

		while(list($curValue) = $this->DB->fetchArray())
			$temp[] = $curValue;

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

			if(count($lang) == 1 || !preg_match('/q=([0-9.]+)/i',trim($lang[1]),$matches)) $pref['1.'.rand(0,9999)] = Functions::strtolower($lang[0]);
			else $pref[$matches[1].rand(0,9999)] = Functions::strtolower($lang[0]);
		}
		krsort($pref);

		return array_shift(array_merge(array_intersect($pref, $availableLangs), $availableLangs));
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
	
	/**
	 * Converts the proprietary TBB1 registration date format to an UNIX timestamp
	 *
	 * @param mixed $date
	 * @return integer
	 */
	protected static function tbb1ConversionConvertRegdate2Time($date) {
		$year = substr($date,0,4);
		$month = substr($date,4,2);
		return mktime(0,0,0,$month,0,$year);
	}
	
	/**
	 * Converts the proprietary TBB1 date format to an UNIX timestamp 
	 *
	 * @param mixed $date
	 * @return integer
	 */
	protected static function tbb1ConversionConvertDate2Time($date) {
		return mktime(substr($date,8,2),substr($date,10,2),0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
	}
	
	/**
	 * Explodes a string using the tabulator char as seperator
	 *
	 * @param string $data
	 * @return array
	 */
	protected static function tbb1ConversionExplodeByTab($data) {
		return explode("\t",$data);
	}
	
	/**
	 * Implodes a string using the tabulator char as seperator
	 *
	 * @param array $data
	 * @return string
	 */
	protected static function tbb1ConversionImplodeByTab(array $data = array()) {
		return implode("\t",$data);
	}
	
	/**
	 * Reads a file and stores the lines in an array
	 *
	 * @param string $file
	 * @return array
	 */
	protected static function tbb1ConversionFileToArray($fileName) {
		if(!file_exists($fileName)) return FALSE;
	
		$fileContents = file_get_contents($fileName,LOCK_SH);	
		
		if($fileContents != '') {
			$fileContents = str_replace("\r\n","\n",$fileContents);
			$fileContents = explode("\n",$fileContents);
			array_pop($fileContents);
		} else {
			$fileContents = array();
		}

		return $fileContents;
	}
	
	/**
	 * Undos the TBB1's mutate() function 
	 *
	 * @param string $text
	 * @return string
	 */
	protected static function tbb1ConversionUnmutate($text) {
		$text = str_replace('&amp;','&',$text);
		$text = str_replace('&quot;','"',$text);
		$text = str_replace('&lt;','<',$text);
		$text = str_replace('&gt;','>',$text);
		return $text;
	}
	
	/**
	 * Replaces all HTML-line breaks (<br> etc) by \n
	 *
	 * @param string $string
	 * @return string
	 */
	protected static function tbb1ConversionBr2Nl($string) {
		$string = str_replace('<br/>',"\n",$string);
		$string = str_replace('<br />',"\n",$string);
		$string = str_replace('<br>',"\n",$string);
		return $string;
	}
	
	/**
	 * Checks if an user file exists
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	protected function tbb1ConversionUserExists($userID) {
		return file_exists($this->pathToTBB1.'/members/'.$userID.'.xbb');
	}
	
	/**
	 * Print the current status of the TBB1 conversion. If $autoLocation is specified,
	 * the browser will automatically redirect to this location.
	 *
	 * @param string $autoLocation
	 */
	protected function tbb1ConversionPrintConversionStatus($autoLocation = '') {
		$this->printHeader($autoLocation);
	
		?>
		<table class="TableStd" width="100%">
			<colgroup>
				<col width="25%"/>
				<col width="75%"/>
			</colgroup>
			<tr><td class="CellCat" colspan="2"><span class="FontCat"><?php echo $this->steps[$this->step-1]; ?></span></td></tr>
			<tr>
				<td class="CellWhite">
					<span class="FontNorm"><?php echo $this->strings['tbb1_conversion_running_info']; ?></span><br/><br/>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['General_data'] ?>:</span></td>
							<td class="CellWhite"><div style="background-color:#000000; padding:0px; margin:0px; height:12px; width:<?php echo $this->tbb1ConversionProperties['statusPre']*2; ?>px; float:left;"></div> <span class="FontNorm"><?php echo $this->tbb1ConversionProperties['statusPre']; ?>%</span></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['Members_data'] ?>:</span></td>
							<td class="CellWhite"><div style="background-color:#000000; padding:0px; margin:0px; height:12px; width:<?php echo $this->tbb1ConversionProperties['statusMembers']*2; ?>px; float:left;"></div> <span class="FontNorm"><?php echo $this->tbb1ConversionProperties['statusMembers']; ?>%</span></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['Topics_data'] ?>:</span></td>
							<td class="CellWhite"><div style="background-color:#000000; padding:0px; margin:0px; height:12px; width:<?php echo $this->tbb1ConversionProperties['statusTopics']*2; ?>px; float:left;"></div> <span class="FontNorm"><?php echo $this->tbb1ConversionProperties['statusTopics']; ?>%</span></td>
						</tr>
						<tr>
							<td class="CellWhite"><span class="FontNorm"><?php echo $this->strings['Other_data'] ?>:</span></td>
							<td class="CellWhite"><div style="background-color:#000000; padding:0px; margin:0px; height:12px; width:<?php echo $this->tbb1ConversionProperties['statusPost']*2; ?>px; float:left;"></div> <span class="FontNorm"><?php echo $this->tbb1ConversionProperties['statusPost']; ?>%</span></td>
						</tr>
					</table>
				</td>
			</tr>
		 </table>
		<?php
	
		$this->printTail();
	}
}

?>