<?php
class BaseController extends Action{
	/**
	* ajax请求返回正确信息
	*/
	public function asuccess($message , $data = array()){
		$result = array(
			'info' => $message,
			'status' => 1,
			'data' => $data,
		);
		return json_encode($result);
	}

	/**
	* ajax请求返回失败信息
	*/
	public function aerror($message , $data = array()){
		$result = array(
			'info' => $message,
			'status' => 0,
			'data' => $data,
		);
		return json_encode($result);
	}
}

