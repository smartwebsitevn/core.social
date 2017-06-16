<?php namespace Core\Support;

class Str
{
	/**
	 * Lay chuoi ben trai
	 *
	 * @param string $str
	 * @param int    $len
	 * @return string
	 */
	public static function left($str, $len = 1)
	{
		return $len ? substr($str, 0, $len) : '';
	}

	/**
	 * Lay chuoi ben phai
	 *
	 * @param string $str
	 * @param int    $len
	 * @return string
	 */
	public static function right($str, $len = 1)
	{
		return $len ? substr($str, $len * -1) : '';
	}

	/**
	 * Loai bo chuoi ben trai
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 * @return string
	 */
	public static function ltrim($haystack, $needles)
	{
		foreach ((array) $needles as $needle)
		{
			if (starts_with($haystack, $needle))
			{
				$haystack = static::right($haystack, strlen($haystack) - strlen($needle));
			}
		}

		return $haystack;
	}

	/**
	 * Loai bo chuoi ben phai
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 * @return string
	 */
	public static function rtrim($haystack, $needles)
	{
		foreach ((array) $needles as $needle)
		{
			if (ends_with($haystack, $needle))
			{
				$haystack = static::left($haystack, strlen($haystack) - strlen($needle));
			}
		}

		return $haystack;
	}

	/**
	 * Loai bo chuoi 2 ben
	 *
	 * @param string       $haystack
	 * @param string|array $needles
	 * @return string
	 */
	public static function trim($haystack, $needles)
	{
		foreach ((array) $needles as $needle)
		{
			$haystack = static::ltrim($haystack, $needle);
			$haystack = static::rtrim($haystack, $needle);
		}

		return $haystack;
	}

	/**
	 * Phan tich namespace
	 *
	 * @param string $name
	 * @param string $delimiter
	 * @param string $default
	 * @return array
	 */
	public static function parseNamespace($name, $delimiter = '::', $default = null)
	{
		$name = static::ltrim($name, $delimiter);

		if (str_contains($name, $delimiter))
		{
			$segments = explode($delimiter, $name, 2);
		}
		else
		{
			$segments = array($default, $name);
		}

		return $segments;
	}

	/**
	 * Phan tich function
	 *
	 * @param string $input
	 * @return array
	 */
	public static function parseFunction($input)
	{
		$segments = explode(':', $input, 2);

		$function = (string) array_get($segments, 0);

		$args = (string) array_get($segments, 1);

		$args = ($args == '') ? [] : str_getcsv($args);

		return [$function, $args];
	}

}