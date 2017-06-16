<?php

class Log_access_library
{
	/**
	 * Duong dan thu muc logs
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Thu muc chua file log
	 *
	 * @var string
	 */
	protected $dir = 'logs/access';

	/**
	 * Format cua ten file log
	 *
	 * @var string
	 */
	protected $file_format = '{date}.log';


	/**
	 * Test
	 */
	public function _t()
	{
		$v = $this->makeFileLog();
//		$v = $this->makeLog();
		$v = $this->log();

		pr($v);
	}

	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->path = APPPATH.$this->dir;

		$this->ci('load')->library('user_agent', null, 'agent');
	}

	/**
	 * Luu log
	 */
	public function log()
	{
		$log = $this->makeLog();

		$this->writeLog($log);
	}

	/**
	 * Tao log
	 *
	 * @return string
	 */
	protected function makeLog()
	{
		$ip = $this->ci('input')->ip_address();

		$time = date('Y-m-d - H:i:s');

		$method = $this->getMethod();

		$url = current_url();

		$agent = implode(' - ', array(
			$this->ci('agent')->browser(),
			$this->ci('agent')->version(),
			$this->ci('agent')->platform(),
		));

		return '['.implode('] [', compact('ip', 'time', 'method', 'url', 'agent')).']';
	}

	/**
	 * Lay method hien tai
	 *
	 * @return string
	 */
	protected function getMethod()
	{
		$post = $this->ci('input')->post();

		return empty($post) ? 'get' : 'post';
	}

	/**
	 * Ghi log
	 *
	 * @param string $log
	 * @return bool
	 */
	protected function writeLog($log)
	{
		$file = $this->makeFileLog();

		return $this->writeFileLog($file, $log);
	}

	/**
	 * Tao duong dan file log
	 *
	 * @return string
	 */
	protected function makeFileLog()
	{
		$date = date('Y-m-d');

		$file = strtr($this->file_format, [
			'{date}' => $date,
		]);

		return $this->path($file);
	}

	/**
	 * Ghi file log
	 *
	 * @param string $file
	 * @param string $log
	 * @return bool
	 */
	protected function writeFileLog($file, $log)
	{
		if ( ! is_dir($dir = dirname($file)))
		{
			mkdir($dir, 0777, true);
		}

		if ( ! $fp = fopen($file, 'ab'))
		{
			return false;
		}

		flock($fp, LOCK_EX);

		fwrite($fp, $log."\n");

		flock($fp, LOCK_UN);

		fclose($fp);

		return true;
	}

	/**
	 * Tao path
	 *
	 * @param string $path
	 * @return string
	 */
	public function path($path = '')
	{
		return $this->path . ($path ? '/'.$path : $path);
	}

	/**
	 * Lay service cua CI
	 *
	 * @param string $p
	 * @return mixed
	 */
	protected function ci($p)
	{
		$CI = get_instance();

		return $CI->$p;
	}

}