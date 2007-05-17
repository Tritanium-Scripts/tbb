<?php

class AdminProfileFields extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminProfileFields');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_profile_fields'),INDEXFILE.'?action=AdminProfileFields&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query("SELECT fieldID,fieldName,fieldType FROM ".TBLPFX."profile_fields");
				$fieldsData = $this->modules['DB']->raw2Array();

				foreach($fieldsData AS &$curField) {
					if($curField['fieldType'] == PROFILE_FIELD_TYPE_TEXT) $curField['_fieldTypeText'] = $this->modules['Language']->getString('Textfield');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_TEXTAREA) $curField['_fieldTypeText'] = $this->modules['Language']->getString('Textarea');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTSINGLE) $curField['_fieldTypeText'] = $this->modules['Language']->getString('Single_selection_list');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI) $curField['_fieldTypeText'] = $this->modules['Language']->getString('Multiple_selection_list');
				}

				$this->modules['Template']->assign(array(
					'fieldsData'=>$fieldsData
				));
				$this->modules['PageParts']->printPage('AdminProfileFields.tpl');
				break;

			case 'AddField':
				$p = Functions::getSGValues($_POST['p'],array('fieldName','fieldRegexVerification','fieldLink','fieldData','fieldType'),'');
				$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);

				if(!in_array($p['fieldType'],array(PROFILE_FIELD_TYPE_SELECTMULTI,PROFILE_FIELD_TYPE_SELECTSINGLE,PROFILE_FIELD_TYPE_TEXT,PROFILE_FIELD_TYPE_TEXTAREA)))
					$p['fieldType'] = PROFILE_FIELD_TYPE_TEXT;

				if($p['fieldLink'] == '') $p['fieldLink'] = '%1$s';

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);

					$fieldData = array();
					if(trim($p['fieldData']) != '')
						$fieldData = explode("\n",str_replace("\r",'',Functions::stripSlashes(trim($p['fieldData']))));

					$this->modules['DB']->query("
						INSERT INTO
							".TBLPFX."profile_fields
						SET
							fieldName='".$p['fieldName']."',
							fieldType='".$p['fieldType']."',
							fieldIsRequired='".$cp['fieldIsRequired']."',
							fieldShowRegistration='".$c['fieldShowRegistration']."',
							fieldShowMemberlist='".$c['fieldShowMemberlist']."',
							fieldData='".Functions::addSlashes(serialize($fieldData))."',
							fieldRegexVerification='".$p['fieldRegexVerification']."',
							fieldLink='".$p['fieldLink']."'
					");

					Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_profile_field'),INDEXFILE.'?action=AdminProfileFields&amp;mode=AddField&amp;'.MYSID);
				$this->modules['PageParts']->printPage('AdminProfileFieldsAddField.tpl');
				break;

			case 'EditField':
				$fieldID = isset($_GET['fieldID']) ? intval($_GET['fieldID']) : 0;

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."profile_fields WHERE fieldID='$fieldID'");
				($this->modules['DB']->getAffectedRows() == 0) ? die('Cannot load data: profile field') : $fieldData = $this->modules['DB']->fetchArray();

				$p = Functions::getSGValues($_POST['p'],array('fieldName','fieldRegexVerification','fieldLink','fieldType'),'',Functions::addSlashes($fieldData));
				$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0,Functions::addSlashes($fieldData));

				$p['fieldData'] = isset($_POST['p']['fieldData']) ? $_POST['p']['fieldData'] : implode("\n",Functions::addSlashes(unserialize($fieldData['fieldData'])));

				if(!in_array($p['fieldType'],array(PROFILE_FIELD_TYPE_SELECTMULTI,PROFILE_FIELD_TYPE_SELECTSINGLE,PROFILE_FIELD_TYPE_TEXT,PROFILE_FIELD_TYPE_TEXTAREA)))
					$p['fieldType'] = PROFILE_FIELD_TYPE_TEXT;

				if($p['fieldLink'] == '') $p['fieldLink'] = '%1$s';

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);

					$fieldData = array();
					if(trim($p['fieldData']) != '')
						$fieldData = explode("\n",str_replace("\r",'',Functions::stripSlashes(trim($p['fieldData']))));

					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."profile_fields
						SET
							fieldName='".$p['fieldName']."',
							fieldType='".$p['fieldType']."',
							fieldIsRequired='".$cp['fieldIsRequired']."',
							fieldShowRegistration='".$c['fieldShowRegistration']."',
							fieldShowMemberlist='".$c['fieldShowMemberlist']."',
							fieldData='".Functions::addSlashes(serialize($fieldData))."',
							fieldRegexVerification='".$p['fieldRegexVerification']."',
							fieldLink='".$p['fieldLink']."'
						WHERE
							fieldID='$fieldID'
					");

					Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'fieldID'=>$fieldID
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_profile_field'),INDEXFILE.'?action=AdminProfileFields&amp;mode=EditField&amp;fieldID=$fieldID&amp;'.MYSID);
				$this->modules['PageParts']->printPage('AdminProfileFieldsEditField.tpl');
				break;

			case 'DeleteField':
				$fieldID = isset($_GET['fieldID']) ? intval($_GET['fieldID']) : 0;

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."profile_fields WHERE fieldID='$fieldID'");
				if($this->modules['DB']->getAffectedRows() != 0)
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE fieldID='$fieldID'");

				Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
				break;
		}
	}
}

?>