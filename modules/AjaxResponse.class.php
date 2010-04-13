<?php
class AjaxResponse {
	const STATUS_SUCCESS = 'SUCC';
	const STATUS_FAIL = 'FAIL';
	const STATUS_WARNING = 'WARNING';

	/**
	 *
	 * @var string
	 */
	protected $status = self::STATUS_FAIL;

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

	public function addValue($key, $value) {
		$this->results[] = array('key'=>$key, 'value'=>$value);
	}

	public function respond() {
		$template = Factory::singleton('Template');

		$template->assign(array(
			'status'=>$this->status,
			'values'=>$this->results,
			'mode'=>'',
			'error'=>$this->error
		));
		$template->display('AjaxResult.tpl');
	}
}