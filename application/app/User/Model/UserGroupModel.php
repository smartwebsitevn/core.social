<?php namespace App\User\Model;

class UserGroupModel extends \Core\Base\Model
{
	protected $table = 'user_group';

	protected $casts = [
		'discount'    => 'float',
		'balance_send_amount_daily' => 'float',
		'payments'    => 'array',
		'permissions' => 'array',
	];

	protected $defaults = [
		'payments'    => [],
		'permissions' => [],
	];

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		switch ($action)
		{
			case 'delete':
			{
				return ! $this->getAttribute('type');
			}
		}

		return parent::can($action);
	}


}