<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */

include('modules/AjaxResponse.class.php');

class FileUploads extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function initializeMe() {
		$this->modules['Language']->addFile('FileUploads');
	}

	public function executeMe() {
		switch($_GET['mode']) {
			case 'SingleUploadAjax':
				$this->handleSingleUploadAjax();
				break;
		}
	}

	protected function handleDeleteSingleUploadAjax() {
		$response = new AjaxResponse();

		$fileID = isset($_GET['fileID']) ? intval($_GET['fileID']) : 0;
		if(!$fileData = FuncFiles::getFileData($fileID)) {
			$response->setError($this->modules['Language']->getString('error_file_not_found'));
			$response->respond(); exit;
		}

		if($this->modules['Auth']->getValue('userIsAdmin') != 1 || USERID != $fileData['userID']) {
			$response->setError($this->modules['Language']->getString('error_access_denied'));
			$response->respond(); exit;
		}

		FuncFiles::deleteFile($fileID);
		
		$response->setStatus(AjaxResponse::STATUS_SUCCESS);
		$response->respond(); exit;
	}

	protected function handleSingleUploadAjax() {
		$response = new AjaxResponse();

		if($this->modules['Auth']->getValue('userAuthUpload') == 1) {
			$response->setError($this->modules['Language']->getString('error_user_not_allowed_to_upload'));
			$response->respond(); exit;
		}

		if(!isset($_FILES['upload']) || is_array($_FILES['upload']['error'])) {
			$response->setError($this->modules['Language']->getString('error_while_uploading'));
			$response->respond(); exit;
		}

		$error = $_FILES['upload']['error'];
		
		if($error != UPLOAD_ERR_OK) {
			$response->setError($this->modules['Language']->getString('error_while_uploading'));
			$response->respond(); exit;
		}

		// TODO: check if enough space available for this user/file
		if(FALSE) {
			$response->setError($this->modules['Language']->getString('error_space_limit_exceeded'));
			$response->respond(); exit;
		}

		$thumbnail = '';
		if(@getimagesize($_FILES['upload']['tmp_name']) !== FALSE) {
			// TODO: create thumbnail
		}

		$this->modules['DB']->queryParams('
			INSERT INTO '.TBLPFX.'files (
				"userID",
				"fileUploadTimestamp",
				"fileName",
				"fileSize",
				"fileData",
				"fileThumbnail"
			) VALUES (
				$1,
				$2,
				$3,
				$4,
				$5,
				$6
			)
		',array(
			USERID,
			time(),
			$_FILES['upload']['name'],
			filesize($_FILES['upload']['tmp_name']),
			file_get_contents($_FILES['upload']['tmp_name']),
			$thumbnail
		));
		$fileID = $this->modules['DB']->getInsertID();

		$response->setStatus(AjaxResponse::STATUS_SUCCESS);
		$response->addValue('fileId', $fileID);
		$response->respond(); exit;
	}

	/**
	 * Handle muplitple uploads at once by submitting $_FILES['uploads']
	 * as an array. This function is currently not in use
	 */
	protected function handleMultipleUploadsAjax() {
		$response = new AjaxResponse;

		if($this->modules['Auth']->getValue('userAuthUpload') != 1) {
			//$response->setError($this->modules['Language']->getString('error_user_not_allowed_to_upload'));
			$response->setError("du hast nicht die erforderlichen Rechte");
			$response->respond(); exit;
		}

		$response->setStatus(AjaxResponse::STATUS_SUCCESS);

		if(isset($_FILES['uploads']) && is_array($_FILES['uploads']['error'])) {
			$this->modules['DB']->query('BEGIN');

			foreach($_FILES['uploads']['error'] AS $file => $error) {
				if($error != UPLOAD_ERR_OK) {
					$response->addResult(array(
						'name'=>$_FILES['uploads']['name'][$file],
						'status'=>AjaxResponse::STATUS_FAIL,
						'error'=>$this->modules['Language']->getString('error_while_uploading'),
						'fileID'=>0
					));
					continue;
				}

				// TODO: check if enough space available for this user/file
				if(FALSE) {
					$response->addResult(array(
						'name'=>$_FILES['uploads']['name'][$file],
						'status'=>AjaxResponse::STATUS_FAIL,
						'error'=>$this->modules['Language']->getString('error_space_limit_exceeded'),
						'fileID'=>0
					));
					continue;
				}
				
				$curThumbnail = '';
				if(getimagesize($_FILES['uploads']['tmp_name'][$file]) !== FALSE) {
					// TODO: create thumbnail
				}

				$this->modules['DB']->queryParams('
					INSERT INTO '.TBLPFX.'files (
						"userID",
						"fileUploadTimestamp",
						"fileName",
						"fileSize",
						"fileData",
						"fileThumbnail"
					) VALUES (
						$1,
						$2,
						$3,
						$4,
						$5,
						$6
					)
				',array(
					USERID,
					time(),
					$_FILES['uploads']['name'][$file],
					filesize($_FILES['uploads']['tmp_name'][$file]),
					file_get_contents($_FILES['uploads']['tmp_name'][$file]),
					$curThumbnail
				));
				$fileID = $this->modules['DB']->getInsertID();

				$response->addResult(array(
					'name'=>$_FILES['uploads']['name'][$file],
					'status'=>AjaxResponse::STATUS_SUCCESS,
					'error'=>'',
					'fileID'=>$fileID
				));
			}

			$this->modules['DB']->query('COMMIT');
		}
		$response->respond();
	}
}