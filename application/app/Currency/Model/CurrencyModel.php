<?php namespace App\Currency\Model;

use App\Currency\CurrencyFactory;

class CurrencyModel extends \Core\Base\Model
{
	protected $table = 'currency';


	/**
	 * Lay is_default
	 *
	 * @return bool
	 */
	protected function getIsDefaultAttribute()
	{
		$default = CurrencyFactory::currency()->getDefault();

		return $this->getKey() === $default->getKey();
	}

	/**
	 * Lay is_current
	 *
	 * @return bool
	 */
	protected function getIsCurrentAttribute()
	{
		$current = CurrencyFactory::currency()->getCurrent();

		return $this->getKey() === $current->getKey();
	}

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
			case 'create_purse':
			{
				$code = $this->getAttribute('code');

				$real_codes = CurrencyFactory::service()->config('real_codes');

				return in_array($code, $real_codes);
			}
		}

		return parent::can($action);
	}

}