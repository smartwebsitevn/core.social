<?php namespace Core\Base;

class ErrorException extends \Exception
{
	/**
	 * Error
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param string     $error
	 * @param string     $message
	 * @param int        $code
	 * @param \Exception $previous
	 */
	public function __construct($error, $message = '', $code = 0, \Exception $previous = null)
	{
		$this->error = $error;

		$message = $message ?: $error;

		parent::__construct($message, $code, $previous);
	}

	/**
	 * Lay error
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

}