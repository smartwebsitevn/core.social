<?php namespace Core\Model;

trait SettingAttributeMakerTrait
{
	/**
	 * Gan setting attribute
	 *
	 * @param array|string $value
	 */
	protected function setSettingAttribute($value)
	{
		$value = is_array($value) ? $value : [];

		$value = $this->handleSettingAttributeValue($value);

		$this->attributes['setting'] = json_encode($value);
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
		$value = json_decode($value, true);

		return is_array($value) ? $value : [];
	}

}