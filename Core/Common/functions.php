
<?php
//调试方法
function mydebug($str){
	echo '<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">';
	echo '<pre>';
	print_r($str);
	echo '</pre>';
	exit();
}

/**
* 载入文件
* @param string 文件路径
**/
function import($file){
	return require_once($file);
}

/**
* 自动载入文件夹下面的所有文件（.php文件？）
* @param array/string 需要载入的文件夹路径
**/
function autoImport($file){
	if(is_array($file)){
		foreach($file as $v){
			autoImport($v);
		}
	}else if(is_dir($file)){
		$childs = scandir($file);
		foreach($childs as $child){
			if(substr($child,0,1) != '.'){
				autoImport($file.'/'.$child);
			}
		}
	}else if(is_file($file)){
		import($file);
	}
}

/**
* 读取或设置配置文件
* @param name array/string 配置名,如果为array,则设置配置
* @param value string 如果value不为空，则设置配置文件name为value，可为空 
**/
function config($name , $value=''){
	$userConfig = S('userConfig'); //读取缓存中配置文件
	if(!$userConfig){
		$userConfig = array();
	}
	if(is_array($name)){ //如果name为数组则为添加配置到配置文件缓存中
		$userConfig = array_merge($userConfig,$name);
		//设置配置文件进缓存
		return S('userConfig',$userConfig); 
	}
	if($value){ //如果value不为空则设置name为value
		$userConfig[$name] = $value;
		return S('userConfig',$userConfig);
	}
	return isset($userConfig[$name])?$userConfig[$name]:false;

}

/**
* 读取或设置缓存（到底是存在文件还是内存缓存？）
* @param name string 读取或设置缓存的名称
* @param value any 设置缓存的值，为null表示删除此缓存，留空表示读取
**/
function S($name,$value="",$life_time = -1){
	if(trim($name) == ''){
		return '';
	}
	$filename = md5($name).'.php';
	$path = "./#Runtime/";
	if(!is_dir($path))__mkdirs($path); //目录不存在则创建目录
	$file = $path.$filename;
	if($value){
		// 写数据，在$life_time为-1的时候，将增大$life_time值以令$life_time不过期
		$life_time = ( -1 == $life_time ) ? '300000000' : $life_time;
		// 准备存入缓存文件的数据，缓存文件使用PHP的die();函数以便保证内容安全，读取时候截取掉die()
		$value = '<?php die();?>'.( time() + $life_time ).serialize($value); // 数据被序列化后保存
		return file_put_contents($file,$value);
	}else if($value === null){
		return @unlink($file); //抑制报错
	}else{
		// 读数据，检查文件是否可读，同时将去除缓存数据前部的内容以返回
		if( !is_readable($file) )return FALSE;
		$arg_data = file_get_contents($file);
		// 获取文件保存的$life_time，检查缓存是否过期
		if( substr($arg_data, 14, 10) < time() ){
			@unlink($file); // 过期则移除缓存文件，返回FALSE
			return FALSE;
		}
		return unserialize(substr($arg_data, 24)); // 数据反序列化后返回
	}
}

/**
 * 获取logic逻辑层的实例
 */
function logic($name){
	$name = ucfirst($name).'Logic';
	$logic = new $name();
	return $logic;
}

/**
 * 获取logic逻辑层的实例
 */
function model($name){
	// 转换name
	$name_len = strlen($name);
	$new_name = '';
	$upper_char = true; // 用于标记是否需要大写下个字母
	for($i=0;$i<$name_len;$i++){
		if('_' == $name[$i]) {
			$upper_char = true;
			continue;
		}
		$char = $upper_char ? strtoupper($name[$i]) : strtolower($name[$i]);
		$new_name .= $char;
		$upper_char = false;
	}
	$model_name = $new_name . 'Model';
	$logic = new $model_name($name);
	return $logic;
}

/**
*获取请求中的参数
*/
function input($name , $default = null , $filter=''){
	$value = $_REQUEST[$name];
	if(!$value){
		return $default;
	}
	if($filter){
		$value = $filter($value);
	}
	return $value;
}

/**
 * 多维数组求交集
 * 参数可传多个数组，但至少是二维
 */
function array_intersect_array(){
	$args = func_get_args ();
	$arrays = [];
	// 将不定长参数的内部转成json
	$arg_len = count($args);
	if($arg_len <= 1){ // 最少两个参数
		return $args;
	}
	for ($i = 0; $i < $arg_len; $i++){
		$row = [];
		foreach($args[$i] as $k=>$one){
			ksort($one);
			$row[$k] = json_encode($one);
		}
		$arrays[$i] = $row;
	}
	// 取交集
	$intersect = $arrays[0];
	for($j=0;$j < $arg_len-1;$j++){
		$intersect = array_intersect($intersect , $arrays[$j+1]);
	}
	//还原数组结构
	$res = [];
	foreach($intersect as $k=>$v){
		$res[$k] = json_decode($v , true);
	}
	return $res;
}


/**
 * 记文件形式调试信息,也可用于记录日志
 * @param $data //内容
 * @param string $filepath 文件存放路径 如为 ./log或log则为日志记录,自动加上日期文件夹
 */
function filedebug($data , $filepath='./filedebug.php'){
	// 如果是日志，则将日志分隔成日期文件夹
	$date = date('Ym/d');
	$filepath = str_replace('log/' , "log/$date/" , $filepath);
	$have = strripos($filepath , '/');
	$path = (false === $have ? '' :  substr($filepath , 0 , $have+1)); // 文件夹
	$file = end(explode('/',$filepath)) ?:'default.php'; // 文件名
	if($path && !is_dir($path)){
		mkdir($path,0777,true);
	}
	$filepath = $path . $file;
	$f = fopen($filepath , 'a+');
	fwrite($f , date('Y-m-d H:i:s').'----------'."\n");
	fwrite($f , var_export($data , true));
	fwrite($f , "\n".'----------');
	fclose($f);
}