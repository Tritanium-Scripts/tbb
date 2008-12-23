<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class AdminProfileFields extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminProfileFields');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('manage_profile_fields'),INDEXFILE.'?action=AdminProfileFields&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
                $this->modules['DB']->query('SELECT "fieldID", "fieldName", "fieldType", "fieldIsLocked" FROM '.TBLPFX.'profile_fields');
				$fieldsData = $this->modules['DB']->raw2Array();

				foreach($fieldsData AS &$curField) {
					if($curField['fieldType'] == PROFILE_FIELD_TYPE_TEXT) $curField['_fieldTypeText'] = $this->modules['Language']->getString('textfield');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_TEXTAREA) $curField['_fieldTypeText'] = $this->modules['Language']->getString('textarea');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTSINGLE) $curField['_fieldTypeText'] = $this->modules['Language']->getString('single_selection_list');
					elseif($curField['fieldType'] == PROFILE_FIELD_TYPE_SELECTMULTI) $curField['_fieldTypeText'] = $this->modules['Language']->getString('multiple_selection_list');
				}

				$this->modules['Template']->assign(array(
					'fieldsData'=>$fieldsData
				));
				$this->modules['Template']->printPage('AdminProfileFields.tpl');
				break;

			case 'AddField':
				$p = Functions::getSGValues($_POST['p'],array('fieldName','fieldRegexVerification','fieldLink','fieldData','fieldType','fieldVarName'),'');
				$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);
				
				$errors = array();

				if(!in_array($p['fieldType'],array(PROFILE_FIELD_TYPE_SELECTMULTI,PROFILE_FIELD_TYPE_SELECTSINGLE,PROFILE_FIELD_TYPE_TEXT,PROFILE_FIELD_TYPE_TEXTAREA)))
					$p['fieldType'] = PROFILE_FIELD_TYPE_TEXT;

				if($p['fieldLink'] == '') $p['fieldLink'] = '%1$s';

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);
					
					$this->modules['DB']->queryParams('SELECT "fieldID" FROM '.TBLPFX.'profile_fields WHERE "fieldVarName"=$1',array($p['fieldVarName']));
					if(trim($p['fieldVarName']) == '' || $this->modules['DB']->numRows() > 0) $errors[] = $this->modules['Language']->getString('error_existing_field_variable_name');

					if(count($errors) == 0) {
						$fieldData = array();
						if(trim($p['fieldData']) != '')
							$fieldData = explode("\n",Functions::str_replace("\r",'',trim($p['fieldData'])));
	
	                    $this->modules['DB']->queryParams('
	                        INSERT INTO
	                            '.TBLPFX.'profile_fields
	                        SET
	                            "fieldName"=$1,
	                            "fieldType"=$2,
	                            "fieldIsRequired"=$3,
	                            "fieldShowRegistration"=$4,
	                            "fieldShowMemberlist"=$5,
	                            "fieldData"=$6,
	                            "fieldRegexVerification"=$7,
	                            "fieldLink"=$8,
	                            "fieldVarName"=$9
	                    ', array(
	                        $p['fieldName'],
	                        $p['fieldType'],
	                        $c['fieldIsRequired'],
	                        $c['fieldShowRegistration'],
	                        $c['fieldShowMemberlist'],
	                        serialize($fieldData),
	                        $p['fieldRegexVerification'],
	                        $p['fieldLink'],
	                        $p['fieldVarName']
	                    ));
	
						Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'errors'=>$errors
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('add_profile_field'),INDEXFILE.'?action=AdminProfileFields&amp;mode=AddField&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminProfileFieldsAddField.tpl');
				break;

			case 'EditField':
				$fieldID = isset($_GET['fieldID']) ? intval($_GET['fieldID']) : 0;

                $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'profile_fields WHERE "fieldID"=$1', array($fieldID));
				($this->modules['DB']->getAffectedRows() == 0) ? die('Cannot load data: profile field') : $fieldData = $this->modules['DB']->fetchArray();
				//if($fieldData['fieldIsLocked'] == 1) die('Cannot edit field: locked field');
				
				$errors = array();

				$p = Functions::getSGValues($_POST['p'],array('fieldName','fieldRegexVerification','fieldLink','fieldType','fieldVarName'),'',$fieldData);
				$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0,$fieldData);

				$p['fieldData'] = isset($_POST['p']['fieldData']) ? $_POST['p']['fieldData'] : implode("\n",unserialize($fieldData['fieldData']));

				if(!in_array($p['fieldType'],array(PROFILE_FIELD_TYPE_SELECTMULTI,PROFILE_FIELD_TYPE_SELECTSINGLE,PROFILE_FIELD_TYPE_TEXT,PROFILE_FIELD_TYPE_TEXTAREA)))
					$p['fieldType'] = PROFILE_FIELD_TYPE_TEXT;

				$p['fieldLink'] = ($p['fieldLink'] == '') ? '%1$s' : Functions::HTMLSpecialChars($p['fieldLink']);

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('fieldIsRequired','fieldShowRegistration','fieldShowMemberlist'),0);

					$this->modules['DB']->queryParams('SELECT "fieldID" FROM '.TBLPFX.'profile_fields WHERE "fieldVarName"=$1 AND "fieldID"<>$2',array($p['fieldVarName'],$fieldID));
					if(trim($p['fieldVarName']) == '' || $this->modules['DB']->numRows() > 0) $errors[] = $this->modules['Language']->getString('error_existing_field_variable_name');
					
					if(count($errors) == 0) {
						$fieldData = array();
						if(trim($p['fieldData']) != '')
							$fieldData = explode("\n",Functions::str_replace("\r",'',trim($p['fieldData'])));
	
	                    $this->modules['DB']->queryParams('
	                        UPDATE
	                            '.TBLPFX.'profile_fields
	                        SET
	                            "fieldName"=$1,
	                            "fieldType"=$2,
	                            "fieldIsRequired"=$3,
	                            "fieldShowRegistration"=$4,
	                            "fieldShowMemberlist"=$5,
	                            "fieldData"=$6,
	                            "fieldRegexVerification"=$7,
	                            "fieldLink"=$8,
	                            "fieldVarName"=$10
	                        WHERE
	                            "fieldID"=$9
	                    ', array(
	                        $p['fieldName'],
	                        $p['fieldType'],
	                        $c['fieldIsRequired'],
	                        $c['fieldShowRegistration'],
	                        $c['fieldShowMemberlist'],
	                        serialize($fieldData),
	                        $p['fieldRegexVerification'],
	                        html_entity_decode($p['fieldLink'], ENT_QUOTES, "UTF-8"),
	                        $fieldID,
	                        $p['fieldVarName']
	                    ));
	                    
						Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'fieldID'=>$fieldID,
					'errors'=>$errors
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('edit_profile_field'),INDEXFILE.'?action=AdminProfileFields&amp;mode=EditField&amp;fieldID=$fieldID&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminProfileFieldsEditField.tpl');
				break;

			case 'DeleteField':
				$fieldID = isset($_GET['fieldID']) ? intval($_GET['fieldID']) : 0;

                $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'profile_fields WHERE "fieldID"=$1 AND "fieldIsLocked"<>1', array($fieldID));
				if($this->modules['DB']->getAffectedRows() != 0)
                    $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'profile_fields_data WHERE "fieldID"=$1', array($fieldID));

				Functions::myHeader(INDEXFILE.'?action=AdminProfileFields&'.MYSID);
				break;
		}
	}
}