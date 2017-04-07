<?php
class IndexController extends Action{
	public function index(){
//		import('App/Logic/Parse.class.php');
//		$logic = new ParseLogic();
		$logic = logic('parse');
		if(!$_POST) {
			echo '';
			exit;
		}
		$param = $_POST;

		// 验证key
		if('c16f77bb1fbb6ab8cbe8df8080f1ffac31ca0dafea10b5d166dc4ef597283ff7' != $param['Key']){
			echo 'Invalid Request!';
			exit;
		}

		$atqq = "[@".$param['QQ'].'] ';
		$msg = $param['Message'];

		switch($param['Event']){ // 监听事件
			case 'KeepAlive' : { // 状态检测
				$echo = logic('crontab')->doing();
				if($echo){
					echo $echo;
					exit;
				}
				break;
			}
			case 'ReceiveTempIM' : { // 陌生人私聊
				$echo = logic('parse')->parse('&&'.$msg , $param['QQ']);
				// if(!$echo){
				// 	$echo = $atqq.' '.$param['NickName'].'：'.$msg;
				// 	echo('<&&>SendMessage<&>379515892<&>'.$echo);
				// 	exit;
				// }
				echo('<&&>SendMessage<&>'.$param['QQ'].'<&>'.$echo);
				break;
			}
			case 'ReceiveNormalIM' : { // 普通qq私聊
				$echo = logic('parse')->parse('&&'.$msg , $param['QQ']);
				// if(!$echo){
				// 	$echo = $atqq.' '.$param['NickName'].'：'.$msg;
				// 	echo('<&&>SendMessage<&>379515892<&>'.$echo);
				// 	exit;
				// }
				echo('<&&>SendMessage<&>'.$param['QQ'].'<&>'.$echo);
				break;
			}
			case 'StatusChanged' : { //切换状态
				// $echo = $param['QQ'].'改变状态为'.$param['QQStatus'];
				// echo('<&&>SendMessage<&>379515892<&>'.$echo);
				break;
			}
			case 'ReceiveClusterIM' : {  // 群消息
				$echo = logic('parse')->parse($msg , $param['QQ']);
				if($param['QQ'] == '497635000' || $param['QQ'] == '2326677485'){
					exit;
				}
				if($echo){
					echo('<&&>SendClusterMessage<&>'.$param['ExternalId'].'<&>'.$echo);
					exit;
				}
					
				break;
			}
			case 'RecallQQEvent' : { //消息撤回
				if('503668993' == $param['QQ'] || '1099035898' == $param['QQ']){
					exit;
				}
				$echo = $atqq."撤回的消息为:\n ".$param['Message'];

				if(!model('qq_info')->is_auth($param['ExternalId'])){
					$echo .="\r\n --将此QQ拉入群,可免费使用该机器人所有功能\r\n(加群529913098可免费去除此提示)";
				}

/**
				if(!strstr($echo , '[图片') && !strstr($echo , '?xml')){ //个性消息样式
					$msg = $param['Message'];
					$qq = $param['QQ'];
				$echo = <<<EOF
<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><msg serviceID="14" templateID="1" action="plugin" actionData="AppCmd://OpenContactInfouin=66600000" a_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=379515892" i_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=379515892" brief="消息撤回提醒" sourceMsgId="0" url="" flag="0" adverSign="0" multiMsgFlag="0"><item layout="0"><summary color="#ff55ff">消息撤回提醒</summary><summary color="#ff66ff">{$atqq}:{$msg}</summary></item><source name="乐兔机器人" icon="http://url.cn/JS8oE7" action="" appid="0" /></msg>
EOF;
				}
				*/
				echo '<&&>SendClusterMessage<&>'.$param['ExternalId'].'<&>'.$echo;
				break;
			}
			case 'AddedToClusterInvite' : { // 受邀请加群 , 自动同意群邀请
//			echo('<&&>JoinCluster<&>'.$param['ExternalId']);
				echo('<&&>AgreeAddedToClusterInvite<&>'.$param['ExternalId'].'<&>true<&>额。。');
				break;
			}
			case 'AddedToCluster' : { // 有人进群
				filedebug($_POST);
				if($param['QQ'] == '503668993'){
					$echo = '大家好,我是乐兔小机器人,以后请多多关照';
				} else {
					$echo = '欢迎'.$param['Nick'].'入群,进群请先看群公告';
				}
				echo('<&&>SendClusterMessage<&>'.$param['ExternalId'].'<&>'.$echo);
				break;
			}
		}

	}
}

