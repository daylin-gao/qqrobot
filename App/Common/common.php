<?php


// 是不是以某字符串开头
function startWith($str,$pattern) {
	if(strpos($str,$pattern) === 0)
		return true;
	else
		return false;
}

// 将以空格隔开的参数转成数组
function arg2arr($arg){
	return explode(' ', $arg);
}


/**
 * __mkdirs
 *
 * 循环建立目录的辅助函数
 *
 * @param dir    目录路径
 * @param mode    文件权限
 */
function __mkdirs($dir, $mode = 0777)
{
	if (!is_dir($dir)) {
		__mkdirs(dirname($dir), $mode);
		return @mkdir($dir, $mode);
	}
	return true;
}

// curl 模拟 post 请求
function curl_post($url,$post_data = array() , $header = false){
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url);//设置链接
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
	curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));//POST数据
	$response = curl_exec($ch);//接收返回信息
	if(curl_errno($ch)){	//出错则显示错误信息
		print curl_error($ch);
	}
	curl_close($ch); //关闭curl链接
	return $response;
}



