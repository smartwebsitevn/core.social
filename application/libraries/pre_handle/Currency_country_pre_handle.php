<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_country_pre_handle extends MY_Pre_handle
{
	/**
	 * Goi cac ham xu ly
	 */
	public function boot()
	{
		$currency = $this->get_currency();
		
		$currency_id = get_cookie('currency_id');
		
		if ($currency_id != $currency->id)
		{
			set_cookie('currency_id', $currency->id, config('cookie_expire', 'main'));
		}
	}
	
	/**
	 * Lay currency
	 * 
	 * @return object
	 */
	protected function get_currency()
	{
		$country = $this->get_country();
		
		$currency = false;
		
		if ($country != 'VN')
		{
			$currency = $this->get_currency_usd();
		}
		
		return $currency ?: currency_get_default();
	}
	
	/**
	 * Lay country hien tai
	 * 
	 * @return string
	 */
	protected function get_country()
	{
		$ip = t('input')->ip_address();
		
		$country = lib('geoip')->country($ip);
		
		$country = data_get($country, 'country.isoCode');
		
		return strtoupper($country);
	}
	
	/**
	 * Lay currency usd
	 * 
	 * @return false|object
	 */
	protected function get_currency_usd()
	{
		$result = array_filter(currency_get_list(), function($row)
		{
			return ($row->code == 'USD' && $row->status);
		});
		
		return head($result);
	}
	
}