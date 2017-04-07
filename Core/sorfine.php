<?php
	/**
	* 定义常量
	* 导入核心文件
	* 读取配置文件
	* 加载各个组件
	* 执行mvc分层操作
	**/
	//定义常量
	//导入核心方法文件
	require_once CORE.'Common/functions.php'; 

	//自动载入的文件夹或文件名
	$import = array( 
		CORE."Common",
		CORE."Controller",
		APP."Common",
		APP."Controller",
		APP.'Logic',
		APP.'Model',
	);
	autoImport($import); 
	
	//读取配置文件
	$sfConfig = include(CORE."Conf/config.php"); 
	$userConfig = include(APP."Conf/config.php");
	config($sfConfig); //将框架自带配置加入缓存
	config($userConfig); //将配置文件加入缓存中

	// 构造执行路由
	$__controller = isset($_REQUEST[config('CONTROLLER_PREFIX')])?$_REQUEST[config('CONTROLLER_PREFIX')]:config('DEFAULT_CONTROLLER');
	$__action = isset($_REQUEST[config('ACTION_PREFIX')])?$_REQUEST[config('ACTION_PREFIX')]:config('DEFAULT_ACTION');

	$controller = $__controller.config('CONTROLLER_SUFFIX');
	$controller = new $controller();
	$action = $__action.config('ACTION_SUFFIX');
	$GLOBALS['__controller'] = $__controller;
	$GLOBALS['__action'] = $__action;
	$controller->$action(); //执行xxcontroller的xx方法



