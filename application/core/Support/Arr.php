<?php namespace Core\Support;

class Arr
{
	/**
	 * Chuyen du lieu sang array
	 *
	 * @param mixed $value
	 * @return array
	 */
	public static function toArray($value)
	{
		if (is_object($value) && method_exists($value, 'toArray'))
		{
			return (array) $value->toArray();
		}

		return (array) $value;
	}

	/**
	 * Tao config dang options
	 *
	 * @param array  $items
	 * @param string $item_key
	 * @param string $item_name
	 * @return array
	 */
	public static function makeConfigOptions(array $items, $item_key = 'key', $item_name = 'name')
	{
		$result = [];

		foreach ($items as $key => $options)
		{
			if ( ! is_string($key) && is_string($options))
			{
				$key = $options;
			}

			$options = is_array($options) ? $options : [$item_name => $options];

			$options = array_add($options, $item_name, $key);

			$options[$item_key] = $key;

			$result[$key] = $options;
		}

		return $result;
	}

	/**
	 * Lay gia tri cua cac keys trong array
	 *
	 * @param array        $array
	 * @param string|array $keys
	 * @return array
	 */
	public static function pick(array $array, $keys)
	{
		$result = [];

		foreach ((array) $keys as $key)
		{
			$result[$key] = array_get($array, $key);
		}

		return $result;
	}

	/**
	 * Loai bo cac gia tri null khoi array
	 *
	 * @param array $array
	 * @return array
	 */
	public static function filterNull(array $array)
	{
		return array_filter($array, function($value)
		{
			return ! is_null($value);
		});
	}

	/**
	 * Chuyen cac key co gia tri null thanh chuoi rong
	 *
	 * @param array $array
	 * @return array
	 */
	public static function mapNullToEmptyString(array $array)
	{
		return array_map(function($value)
		{
			return is_null($value) ? '' : $value;
		}, $array);
	}
} 