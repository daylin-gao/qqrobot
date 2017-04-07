<?php
	/**
	* 定义全局常量
	* 执行初始化操作
	**/
	// error_reporting(E_ERROR);
	error_reporting(0);
	// error_reporting(E_ALL);
	define("CORE",'./Core/');
	define("APP",'./App/');

	define('DB_HOST', '127.0.0.1');
	define('DB_PASS', 'Gaodunming5');
	define('DB_PORT', '3306');
	define('DB_USER', 'root');
	define('DB_NAME', 'qqjqr');
	define('DB_PDO', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8');

	require CORE.'sorfine.php';