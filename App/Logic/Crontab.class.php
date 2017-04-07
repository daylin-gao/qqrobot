<?php
/**
 * User: porter
 * Date: 2016/12/23
 * Time: 1:14
 * Description: 定时任务
 */
class CrontabLogic {
	// 解析指令
	public function doing(){
		$echo = '';

		$echo .= $this->qq_push(); // 消息推送

		return $echo;

	}

	// qq消息推送
	private function qq_push(){
		$push = model('qq_push')->getPush(); 
		return $push;
	}
}