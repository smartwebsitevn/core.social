<?php namespace App\LogActivity\Library;

use App\Admin\AdminFactory as AdminFactory;
use App\User\UserFactory as UserFactory;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;
use App\LogActivity\ActivityOwner\Admin as AdminOwner;
use App\LogActivity\ActivityOwner\User as UserOwner;
use App\LogActivity\ActivityOwner\System as SystemOwner;
use TF\Support\Collection;

abstract class ActivityLogger
{
	/**
	 * Key cua driver
	 *
	 * @var string
	 */
	protected $key;


	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	abstract public function info();

	/**
	 * Lay key
	 *
	 * @return string
	 */
	public function key()
	{
		return $this->key ?: class_basename($this);
	}

	/**
	 * Luu log
	 *
	 * @param string $action
	 * @param string $key
	 * @param ActivityOwner  $owner
	 * @param array  $context
	 * @return LogActivityModel
	 */
	public function log($action, $key, $owner = null, array $context = [])
	{
		$owner = $owner ?: $this->makeOwnerCurrent();

		return LogActivityModel::createLog([
			'logger_key' => $this->key(),
			'action'     => $action,
			'key'        => $key,
			'owner_type' => $owner->getType(),
			'owner_key'  => $owner->getKey(),
			'context'    => $context,
		]);
	}

	/**
	 * Tao Owner tuong ung hien tai cua he thong
	 *
	 * @return ActivityOwner
	 */
	public function makeOwnerCurrent()
	{
		if (get_area() == 'admin')
		{
			return new AdminOwner();
		}

		if (UserFactory::auth()->logged())
		{
			return new UserOwner();
		}

		return new SystemOwner;
	}

	/**
	 * Lay ten logger
	 *
	 * @return string
	 */
	public function getName()
	{
		return array_get($this->info(), 'name');
	}

	/**
	 * Lay ten action
	 *
	 * @param string $action
	 * @return string
	 */
	public function getActionName($action)
	{
		$lang = 'button_'.$action;

		$name = lang('button_'.$action);

		return $name == $lang ? $action : $name;
	}

	/**
	 * Lay danh sach cac action
	 *
	 * @return array
	 */
	public function getActions()
	{
		return [];
	}

	/**
	 * Tao log message
	 *
	 * @param LogActivityModel $log
	 * @return null|string|array
	 */
	public function getMessage(LogActivityModel $log)
	{
		return null;
	}

	/**
	 * Lay danh sach log
	 *
	 * @param array $filter
	 * @param array $input
	 * @return Collection
	 */
	public function listLogs(array $filter = [], array $input = [])
	{
		$filter['logger_key'] = $this->key();

		$input = array_add($input, 'order', ['id', 'asc']);

		$list = (new LogActivityModel)->newQuery()->filter_get_list($filter, $input);

		return LogActivityModel::makeCollection($list);
	}

}