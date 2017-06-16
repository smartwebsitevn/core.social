<?php namespace App\Product;

use Core\Support\Traits\ErrorLangTrait;

class Service
{
	use ErrorLangTrait;

	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('config')->load('mod/product', true, true);
	}

	/**
	 * Lay config
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function config($key = null, $default = null)
	{
		return array_get(config('mod/product', ''), $key, $default);
	}

	/**
	 * Tao danh sach type_types
	 *
	 * @param string $type
	 * @return array
	 */
	public function makeListTypeTypes($type)
	{
		$list = $this->config('type_types.'.$type);

		$list = array_map(function($key)
		{
			return [
				'key'  => $key,
				'name' => lang('name_'.$key),
			];
		}, $list);

		return $list;
	}

	/**
	 * Lay duong dan file error lang
	 *
	 * @return string
	 */
	protected function getErrorLangPath()
	{
		return 'modules/product/common';
	}
}