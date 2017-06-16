<?php namespace App\LogActivity\Model;

use App\LogActivity\LogActivityFactory;
use App\LogActivity\Library\ActivityLogger as ActivityLogger;

class LogActivityModel extends \Core\Base\Model
{
	protected $table = 'log_activity';

	protected $casts = [
		'context' => 'array',
	];

	protected $defaults = [
		'context' => [],
	];


	/**
	 * Tao log
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function createLog(array $attributes)
	{
		$attributes = array_add($attributes, 'ip', t('input')->ip_address());
		$attributes = array_add($attributes, 'user_agent', t('input')->user_agent());
		$attributes = array_add($attributes, 'session_id', t('session')->session_id);
		$attributes = array_add($attributes, 'url', current_url());

		return static::create($attributes);
	}

	/**
	 * Lay logger_name
	 *
	 * @return string
	 */
	protected function getLoggerNameAttribute()
	{
		return $this->loggerInstance()->getName();
	}

	/**
	 * Lay action_name
	 *
	 * @return string
	 */
	protected function getActionNameAttribute()
	{
		$action = $this->getAttribute('action');

		return $this->loggerInstance()->getActionName($action);
	}

	/**
	 * Lay message
	 *
	 * @return array|null|string
	 */
	protected function getMessageAttribute()
	{
		return $this->loggerInstance()->getMessage($this);
	}

	/**
	 * Lay doi tuong ActivityLogger
	 *
	 * @return ActivityLogger
	 */
	public function loggerInstance()
	{
		$key = $this->getAttribute('logger_key');

		return LogActivityFactory::logger($key);
	}
}