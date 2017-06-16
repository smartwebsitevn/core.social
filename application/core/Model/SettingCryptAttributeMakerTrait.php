<?php namespace Core\Model;

trait SettingCryptAttributeMakerTrait
{
	/**
	 * Gan setting attribute
	 *
	 * @param array $value
	 */
	protected function setSettingAttribute($value)
	{
		$value = is_array($value) ? $value : [];

		$value = $this->handleSettingAttributeValue($value);

		$this->attributes['setting'] = security_encrypt(json_encode($value), 'encode');
	}

	/**
	 * Xu ly gia tri cua setting attribute
	 *
	 * @param array $value
	 * @return array
	 */
	protected function handleSettingAttributeValue(array $value)
	{
		return $value;
	}

	/**
	 * Lay setting attribute
	 *
	 * @param array|string $value
	 * @return array
	 */
	protected function getSettingAttribute($value)
	{
		$value = security_encrypt($value, 'decode');

		$value = json_decode($value, true);

		return is_array($value) ? $value : [];
	}

}