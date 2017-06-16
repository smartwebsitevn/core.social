<?php namespace App\Product\Library\Provider;

class TranTopupResponse extends TranResponse
{
	/**
	 * Tao response success
	 *
	 * @param array $response
	 * @return static
	 */
	public static function success(array $response = [])
	{
		return new static(array_merge($response, [
			'status' => true,
		]));
	}
}