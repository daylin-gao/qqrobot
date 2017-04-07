<?php
/**
 * User: porter
 * Date: 2016/12/13
 * Time: 17:02
 * Description: 解析指使
 */
class ParseLogic {
	// 解析指令
	public function parse($msg , $qq){
		$param = $_POST;
		$atqq = '[@'.$qq."] ";
		$IS_ADMIN = ($qq == '379515892' ? true : false);
		// 记录数据库
		$con = array(
			'group_id' => $param['ExternalId'],
			'group_name' => $param['Name'],
			'qq_id' => $param['QQ'],
			'qq_nick' => $param['Nick'] ? : $param['NickName'],
			'message' => $param['Message'],
			'create_time' => time(),
		);
		model('qq_msg_log')->insert($con);


		if(startWith($msg , '$porter')){ // 检测是否是特定命令
			$echo = $atqq."找porter干嘛？";
			return $echo;
		}

		if(startWith($msg , '&百度')){
			$args = arg2arr($msg);
			$echo = $atqq.",您要搜索的内容 '".$args[1]."' 地址为  ";
			$echo .= "http://baidu.com/s?wd=".urlencode($args[1]);
			return $echo;
		}

		if(startWith($msg , '&房贷计算器')){
			$echo  = '房贷计算器：http://newhouse.fang.com/house/tools.htm';
			return $echo;
		}

		if(startWith($msg , '&授权列表') && $IS_ADMIN){
			$echo = model('QqInfo')->getQQList();
			return $echo;
		}

		if(startWith($msg , '&增加授权') && $IS_ADMIN){
			$args = arg2arr($msg);
			if(!$args[1] || !$args[2]){
				$echo = "授权格式: #增加授权 群|qq 号码 备注";
				return $echo;
			}
			$type = ($args[1] == '群'?1:2);
			$echo = model('QqInfo')->addQQ($type , $args[2] , $args[3]);
			return $echo;
		}

		ob_clean();
		if(startWith($msg , '&绝对清屏') && $IS_ADMIN){
			$echo = <<<EOF
<?xml version='1.0' encoding='UTF-8' standalone='yes' ?><msg serviceID="14" templateID="1" action="plugin" actionData="AppCmd://OpenContactInfouin=66600000" a_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=503668993" i_actionData="mqqapi://card/show_pslcard?src_type=internal&amp;source=sharecard&amp;version=1&amp;uin=503668993" brief="绝对清屏" sourceMsgId="0" url="" flag="0" adverSign="0" multiMsgFlag="0"><item layout="0"><title size="200000" color="#FF6666">绝对清屏</title><title size="35" color="#33CC99">发现违规信息
本群发起绝对清屏</title><title size="35" color="#33CC99">绝对清屏</title></item><source name="" icon="" action="" appid="-1" /><source name="乐兔机器人" icon="http://url.cn/JS8oE7" action="" appid="0" /></msg>
EOF;
		return $echo;
		}

		if(startWith($msg , '&&') || strstr($msg , '[@503668993]') || strstr($msg , '[@1099035898]')) { // 智能聊天




			if(strstr($msg , '白菜的') || strstr($msg , '大意食精粥') ){
				$story = array(
					'白菜上学的时候，坐在他前面的妹子因为玩手机被老师发现了，怒斥道：“你一姑娘再这样下去，以后怎么赚钱，能养的活自己吗？谁会养你呢？”，白菜一听当时就怒了，站起来一拍桌子，大吼了声：“我来养！！！”，顿时班里一片寂静，妹子转过头瞅了瞅白菜，随即重重的把手机摔在了地上，从此安心上课。',

					'从前，有一个农夫白菜，家里老是丢鸡，他便设下陷阱，抓住了一只狐狸，邻居对他说，‘这狐狸太可怜了，你放了他吧！’，白菜看了看，也觉得狐狸可怜，便大发善心，把那只狐狸给放了，从哪天开始，农夫每天早上起来，便会发现灶头上放着一碗热气腾腾的白粥，……”“ 白菜每天起床，便喝了白粥下地干活，日子过得有滋有味，直到有一天，一位和尚路过，告诉白菜，你家里妖气太重了，恐怕是出了妖孽，白菜起初不以为意，将和尚喝退，不过心中却暗暗留了心思，有一天，天还没亮，他便悄悄地起来，想要看看那白粥究竟是怎么来的，不看还好，这一看，你知道他看到什么么？“一只狐狸，正坐在他家灶头上，对着那碗口打飞机',

					'白菜小时候家里穷，喝不起饮料，看着同学们都买牛奶喝，白菜也想尝尝，就半夜三更跑到邻居家的牛棚里喝牛奶，感觉一股香气，很浓郁，很丝滑，那牛也很配合，喝了半个多月，一天邻居给白菜家端来一盆牛肉，白菜很伤心，以后再也喝不到牛奶了，哭了起来，他爹过来安慰他说：“傻孩子，哭啥，有肉就快点吃，你看，这公牛肉多有嚼劲啊”',

					'生物课上，教授正在讲解精子构造。当教授讲到精子的主要成分是蛋白质和葡萄糖时，白菜突然站起来提问：“那为什么一点都不甜呢？”全班寂静，教授镇静的说：“因为感受甜味的味蕾在舌尖，不是在舌根。'
				);
				return $story[mt_rand(0 , count($story)-1)];
			}





			// if(!model('qq_info')->is_auth($param['ExternalId']) && !in_array($_REQUEST['Event'] , array('ReceiveTempIM','ReceiveNormalIM'))){
			// 	if(mt_rand(0,10) <= 3){
			// 		$echo = "加群529913098可免费使用该机器人所有功能".date('Y-m-d H:i:s');
			// 		return $echo;
			// 	}
			// 	// return false;
			// }

			$ai = config('ai'); // AI相关配置信息
			$post_data = array(
				'key' => $ai['key'],
			);

			$msg = str_replace(array('&&','[@503668993]','[@1099035898]') , '' , $msg);
			$post_data['info'] = $msg;

			$result = json_decode(curl_post($ai['url'] , $post_data) , true);

			// 将问答存入数据库
			$con = array(
				'question' => $msg,
				'answer' => $result['text'],
				'create_time' => time(),
			);
			model('qq_qa')->insert($con);

			
			$echo  = $atqq.$result['text'];
			return $echo;

		}

		// 没授权的群，有1/10的概率回复提示
		if(!model('qq_info')->is_auth($param['ExternalId']) && !in_array($_REQUEST['Event'] , array('ReceiveTempIM','ReceiveNormalIM'))){
			if(mt_rand(0,10) <= 1){
				$echo = "加群529913098可免费使用该机器人所有功能".date('Y-m-d H:i:s');
				return $echo;
			}
		}
		return false;
	}

}