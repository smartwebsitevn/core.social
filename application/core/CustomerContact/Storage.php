<?php namespace Core\CustomerContact;

class Storage
{
	/**
	 * Key luu tru
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Han ton tai cua cookie
	 *
	 * @var
	 */
	protected $cookie_expire;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $key
	 * @param int    $cookie_expire
	 */
	public function __construct($key, $cookie_expire = null)
	{
		$this->key = $key;

		$this->cookie_expire = $cookie_expire ?: 30*24*60*60;
	}

	/**
	 * Lay key luu tru
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Luu data
	 *
	 * @param $data
	 */
	public function set($data)
	{
		set_cookie($this->key, json_encode($data), $this->cookie_expire);
	}

	/**
	 * Lay data
	 *
	 * @return mixed|null
	 */
	public function get()
	{
		$data = get_cookie($this->key);

		return $data ? json_decode($data, true) : null;
	}

	/**
	 * Xoa thong tin
	 */
	public function delete()
	{
		delete_cookie($this->key);
	}
}