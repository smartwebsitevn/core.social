<?php namespace Core\Support;

class Phone
{
	/**
	 * Xu ly phone
	 *
	 * @param string $phone
	 * @return string
	 */
	public static function handlePhone($phone)
	{
		// Loai bo cac ki tu khong phai la so
		$phone = preg_replace('#[^0-9]#', '', $phone);

		// Xu ly ma quoc gia (+84)
		$phone = preg_replace('#^\+?84#', '0', $phone);

		return $phone;
	}

	/**
	 * Kiem tra dinh dang cua phone
	 *
	 * @param string $phone So dien thoai co dang 0***
	 * @return bool
	 */
	public static function validPhone($phone)
	{
		return (
			in_array(strlen($phone), [10, 11])
			&& preg_match('#^0[0-9]+$#', $phone)
		);
	}

	/**
	 * Lay nha mang cua phone
	 *
	 * @param string $phone
	 * @param string $pre_number
	 * @return string|null
	 */
	public static function getProvider($phone, &$pre_number = null)
	{
		foreach (static::listProviderPreNumber() as $provider => $pre_numbers)
		{
			foreach ($pre_numbers as $num)
			{
				if ( ! preg_match('#^'.$num.'#', $phone)) continue;

				$pre_number = $num;

				return $provider;
			}
		}

		return null;
	}

	/**
	 * Lay danh sach dau so cua cac nha mang
	 *
	 * @return array
	 */
	public static function listProviderPreNumber()
	{
		return config('provider_pre_number', 'mod/phone');
	}

}