<?php
//基层action,提供基本方法，做interface
class Action{
	public function display($tpl=''){
		if(!$tpl){
			$tpl = $GLOBALS['__controller'].'/'.$GLOBALS['__action'];
		}
		@ob_start();
		include APP.'View/'.$tpl.'.html';
	}
}
