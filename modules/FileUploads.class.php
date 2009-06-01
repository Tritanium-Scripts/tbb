<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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

	public function executeMe() {
		switch($_GET['mode']) {
			case 'UploadAjax':
				break;
		}
	}

	protected function handleUploadAjax() {
		$response = new AjaxResponse;

		if($this->modules['Auth']->getValue('userAuthUpload') != 1) {
			$response->setError($this->modules['Language']->getString('error_user_not_allowed_to_upload'));
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
	}
}