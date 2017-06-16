<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queue_session_library
{
	/**
	 * Test
	 */
	public function _t()
	{
		/* for ($i = 1; $i <= 10; $i++)
		{
			mod('queue')->push('deposit_sms', [1, $i*1000, []]);
		} */
		
		//$this->run('email', 2, 2.5);
	}
	
	/**
	 * Xu ly cong viec theo phien
	 * 
	 * @param string $key
	 * @param int $quantity
	 * @param int $timeout
	 */
	public function run($key, $quantity, $timeout)
	{
		if ($this->has_session_running($key, $timeout))
		{
			return;
		}
		
		$id = now();
		
		$this->run_session($key, $id, $quantity);
		
		if ($this->is_session_id($key, $id))
		{
			$this->delete_session($key);
		}
	}
	
	/**
	 * Thuc hien chay phien xu ly
	 * 
	 * @param string $key
	 * @param string $id
	 */
	protected function run_session($key, $id, $quantity)
	{
		for ($i = 1; $i <= $quantity; $i++)
		{
			// Cap nhat time xu ly cua phien
			$this->update_session($key, $id);
			
			// Thuc hien xu ly cong viec
			$queue = mod('queue')->work($key);
			
			if (
				! $queue // Khong co cong viec nao can xu ly
				|| ! $this->is_session_id($key, $id) // Phien dang xu ly khong phai la phien hien tai
			)
			{
				return;
			}
		}
	}
	
	/**
	 * Kiem tra co phien dang chay hay khong
	 * 
	 * @param string 	$key
	 * @param int 		$timeout
	 * @return boolean
	 */
	protected function has_session_running($key, $timeout)
	{
		$session = $this->get_session($key);
	
		return ! $this->session_expired($session, $timeout);
	}
	
	/**
	 * Kiem tra session co bi het han hay khong
	 * 
	 * @param array $session
	 * @param int 	$timeout
	 * @return boolean
	 */
	protected function session_expired(array $session, $timeout)
	{
		return (
			! $session['id']
			|| $session['time'] + $timeout < now()
		);
	}
	
	/**
	 * Lay thong tin phien xu ly
	 * 
	 * @param string $key
	 * @return array
	 */
	protected function get_session($key)
	{
		$param = $this->get_session_param($key);
		
		$session = setting_get($param);
		
		return $session ?: [
			'id' 	=> 0,
			'time' 	=> 0,
		];
	}
	
	/**
	 * Cap nhat time xu ly cua phien
	 * 
	 * @param string $key
	 * @param string $id
	 */
	protected function update_session($key, $id)
	{
		$param = $this->get_session_param($key);
		
		model('setting')->set($param, [
			'id' 	=> $id,
			'time' 	=> now(),
		]);
	}
	
	/**
	 * Xoa thong tin phien xu ly
	 * 
	 * @param string $key
	 */
	protected function delete_session($key)
	{
		$param = $this->get_session_param($key);
		
		model('setting')->del($param);
	}
	
	/**
	 * Lay ten bien cua phien xu ly
	 * 
	 * @param string $key
	 * @return string
	 */
	protected function get_session_param($key)
	{
		return 'queue_session' . ($key ? '_'.$key : $key);
	}

	/**
	 * Kiem tra session_id hien tai
	 * 
	 * @param string $key
	 * @param string $id
	 * @return boolean
	 */
	protected function is_session_id($key, $id)
	{
		$session = $this->get_session($key);
		
		return $session['id'] == $id;
	}

	/**
	 * Luu log
	 *
	 * @param string $content
	 */
	protected function log($content)
	{
		file_put_contents(APPPATH.'logs/queue.txt', $content.PHP_EOL, FILE_APPEND);
	}
	
}