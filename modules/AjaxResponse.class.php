<?php
class AjaxResponse {
	const STATUS_SUCCESS = 'SUCC';
	const STATUS_FAIL = 'FAIL';
	const STATUS_WARNING = 'WARNING';

	/**
	 *
	 * @var string
	 */
	protected $status = 'FAIL';

	protected $error = '';

	protected $results = array();

	public function setStatus($status) {
		if($status != self::STATUS_FAIL && $status != self::STATUS_SUCCESS && $status != self::STATUS_WARNING)
			throw new Exception('Unknown ajax response status');

		$this->status = $status;
	}

	public function setError($error) {
		$this->error = $error;
	}

	public function setResults(array $results) {
		$this->results = $results;
	}

	public function addResult(array $result) {
		$this->results[] = $result;
	}

	public function respond() {
		$template = Factory::singleton('Template');

		$template->assign(array(
			'status'=>$status,
			'values'=>$results
		));
		$template->display('AjaxResult.tpl');
	}
}