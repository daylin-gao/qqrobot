<?php

class Logger {

	const OFF = 2147483647;
	const FATAL = 50000;
	const ERROR = 40000;
	const WARN = 30000;
	const INFO = 20000;
	const DEBUG = 10000;
	const ALL = -2147483647;

	private $logLevel;
	public static $DEFAULT_LOG_LEVEL = Logger::DEBUG;
	public static $fileType = '.log';
	public static $loggers = array();
	private $name;
	private static $_fp = null;
	private static $_fps = array();
	private $fp = null;
	private $DAY = null;

	public static function getLogger($name, $path, $setday = true) {
		return new Logger($name, $path, $setday);
	}

	public static function closeAll() {
		if (self::$_fp) {
			fclose(self::$_fp);
			self::$_fp = null;
		}
		foreach (self::$_fps as $fp) {
			fclose($fp);
		}
		self::$_fps = array();
	}

	public function __destruct() {

	}

	private function getFileHandle() {
		$DAY = date('Ymd');
		if ($this->fp && is_resource($this->fp)) {
			if ($DAY == $this->DAY) {
				return $this->fp;
			} else {
				$this->DAY = $DAY;
				fclose($this->fp);
				$this->fp = null;
			}
		}
		$key = $this->name;
		$file = self::$loggers[$key];
		if (strpos($file, '{DAY}')) {
			$file = str_replace('{DAY}', $DAY, $file);
		}
		if (!is_dir(dirname($file))) {
			mkdir(dirname($file));
		}
		self::$_fps[$key] = fopen($file, 'ab');
		$this->fp = self::$_fps[$key];
		return $this->fp;
	}

	private function __construct($name, $path, $setday) {
		$this->name = $key = $name;
		$day = $setday ? '_{DAY}' : '';
		if (!isset(self::$loggers[$key])) {
			self::$loggers[$key] = $path . '/' . $this->name . $day . self::$fileType;
		}
		$this->logLevel = self::$DEFAULT_LOG_LEVEL;
		$this->DAY = date('Ymd');
	}

	private function log($message, $level, $tag) {
		if ($level >= $this->logLevel) {
			$now = microtime(true);
			$trace = debug_backtrace();
			$fp = $this->getFileHandle();
			fprintf($fp, "%s,%06d [%s:%04d][%s] %s - %s\n", date('m/d/y H:i:s', intval($now)), 1000000 * ($now - intval($now)), basename($trace[1]['file']), $trace[1]['line'], $tag, $this->name, is_string($message) ? $message : var_export($message, true));
		}
	}

	public function isDebugEnabled() {
		return $this->logLevel <= self::DEBUG;
	}

	public function isInfoEnabled() {
		return $this->logLevel <= self::INFO;
	}

	public function debug($message) {
		$this->log($message, self::DEBUG, 'DEBUG');
	}

	public function info($message) {
		$this->log($message, self::INFO, 'INFO');
	}

	public function warn($message) {
		$this->log($message, self::WARN, 'WARN');
	}

	public function error($message) {
		$this->log($message, self::ERROR, 'ERROR');
	}

	public function fatal($message) {
		$this->log($message, self::FATAL, 'FATAL');
	}

	public function trace($message) {
		$tracks = debug_backtrace();
		foreach ($tracks as $track) {
			$this->log($message . " - {$track['function']} ({$track['file']}:{$track['line']})", self::FATAL, 'TRACE');
		}
	}

}
