<?php namespace App\Product\Job;

class Migration
{
	protected $table = 'product_vtc';

	public static function _t()
	{
		$me = new static;

		$v = $me->handle();

		pr($v, 0);
	}

	public function handle()
	{
//		$this->makeConstName();

//		$this->makeProductKey();

//		$this->makeVtcKeysConnection();

//		$this->makeVnptEpayKeysConnection();
	}

	// --------------------------------------------------------------------

	/**
	 * Tao du lieu cho cac column dang hang so
	 */
	protected function makeConstName()
	{
		$products = t('db')->get($this->table)->result();

		foreach ($products as $product)
		{
			$type_name = $this->config('product_types.'.$product->type);

			$type_type_name = $this->config('product_types_type.'.$type_name.'.'.$product->type_type);

			t('db')->where('id', $product->id)
				   ->update($this->table, compact('type_name', 'type_type_name'));
		}

		pr($products, 0);
	}

	/**
	 * Lay config
	 *
	 * @param string $key
	 * @return mixed
	 */
	protected function config($key = null)
	{
		$config = [];

		// Type
		foreach (array('code', 'topup_mobile', 'topup_game', 'ship', 'voip', 'topup_mobile_post') as $k => $v)
		{
			$config['product_types'][$k+1] = $v;
			$config['product_type_'.$v] = $k+1;
		}

		// Type types
		$_ = array();
		$_['code'] = array(
			'mobi', 'vina', 'viettel', 'gmobile', 'beeline', 'vnmobile', 'vcoin', 'gate', 'zingcard',
			'oncash', 'megacard', 'garena', 'carot', 'mycard', 'mobay', 'zygal', 'bitdefender',
			'steam', 'like', '7554', 'softnyx', 'kav', 'kis', 'tenlua', 'sfone', 'site',
		);

		$_['topup_mobile'] 	= array('mobi', 'vina', 'viettel', 'gmobile', 'beeline', 'vnmobile');

		$_['topup_mobile_post'] = array('mobi', 'vina', 'viettel', 'gmobile', 'beeline', 'vnmobile');

		$_['topup_game'] = array(
			'vcoin', 'gate', 'zingxu', 'oncash', 'onesoft-bigone', 'bees_elearning', 'bluesea_galaxycity',
			'hdc-myfish', 'hdc-myzoo', 'hdc-khuvuondiadang', 'hdc-vegas', 'hdc-khuvuonvuive', 'hocmai',
			'qt_m4me', 'sdc-babylon', 'sdc-vking', 'st_worldfone', 'thudo_ibet', 'thudo_ionline', 'garena',
			'truongtructuyen', 'dcom_viettel', 'dcom_viettel_post', 'evn', 'tv_vtc', 'asia_soft', 'mcash',
		);

		foreach ($_ as $t => $vs)
		{
			foreach ($vs as $k => $v)
			{
				$config['product_types_type'][$t][$k+1] = $v;
				$config['product_'.$t.'_'.$v] = $k+1;
			}
		}

		return array_get($config, $key);
	}

	// --------------------------------------------------------------------

	/**
	 * Tao product key
	 */
	protected function makeProductKey()
	{
		$products = t('db')->get($this->table)->result();

		foreach ($products as $product)
		{
			$type = $product->type_name == 'code' ? 'card' : $product->type_name;

			$key = $type.'_'.$product->type_type_name.'_'.$product->type_value;

			t('db')->where('id', $product->id)
				   ->update($this->table, compact('key'));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Tao key ket noi cua vtc
	 */
	protected function makeVtcKeysConnection()
	{
		$products = t('db')->get($this->table)->result();

		foreach ($products as $product)
		{
			$provider = $this->getVtcProviderKey($product->type_name, $product->type_type_name);

			if ( ! $provider) continue;

			$keys_connection = json_decode($product->keys_connection, true) ?: [];

			$keys_connection['Vtc'] = $provider.'_'.$product->type_value;

			$keys_connection = json_encode($keys_connection);

			t('db')->where('id', $product->id)
				   ->update($this->table, compact('keys_connection'));
		}
	}
	
	/**
	 * Lay key ket noi cua nha cung cap ben vtc
	 *
	 * @param string $type
	 * @param string $type_type
	 * @return string
	 */
	protected function getVtcProviderKey($type, $type_type)
	{
		$list = array();

		$list['code']['viettel'] = 'VTC0027';
		$list['code']['vina'] = 'VTC0028';
		$list['code']['mobi'] = 'VTC0029';
		$list['code']['gmobile'] = 'VTC0173';
		$list['code']['vnmobile'] = 'VTC0154';
		$list['code']['sfone'] = 'VTC0030';

		$list['code']['zingcard'] = 'VTC0067';
		$list['code']['gate'] = 'VTC0068';
		$list['code']['vcoin'] = 'VTC0114';
		$list['code']['mycard'] = 'VTC0185';
		$list['code']['zygal'] = 'VTC0191';
		$list['code']['oncash'] = 'VTC0166';
		$list['code']['bitdefender'] = 'VTC0144';
		$list['code']['garena'] = 'VTC0319';
		$list['code']['steam'] = 'VTC0355';
		$list['code']['like'] = 'VTC0321';
		$list['code']['7554'] = 'VTC0171';
		$list['code']['softnyx'] = 'VTC0321';
		$list['code']['kav'] = 'VTC0365';
		$list['code']['kis'] = 'VTC0366';
		$list['code']['mobay'] = 'VTC0360';
		$list['code']['tenlua'] = 'VTC0378';


		$list['topup_mobile']['viettel'] = 'VTC0056';
		$list['topup_mobile']['vina'] = 'VTC0057';
		$list['topup_mobile']['mobi'] = 'VTC0058';
		$list['topup_mobile']['vnmobile'] = 'VTC0176';
		$list['topup_mobile']['gmobile'] = 'VTC0177';


		$list['topup_mobile_post']['viettel'] = 'VTC0329';
		$list['topup_mobile_post']['vina'] = 'VTC0201';
		$list['topup_mobile_post']['mobi'] = 'VTC0130';


		$list['topup_game']['vcoin'] = 'VTC0115';
		$list['topup_game']['gate'] = 'VTC0187';
		$list['topup_game']['zingxu'] = 'VTC0307';
		$list['topup_game']['oncash'] = 'VTC0308';
		$list['topup_game']['hocmai'] = 'VTC0040';
		$list['topup_game']['truongtructuyen'] = 'VTC0041';
		$list['topup_game']['dcom_viettel'] = 'VTC0217';
		$list['topup_game']['dcom_viettel_post'] = 'VTC0219';
		$list['topup_game']['evn'] = 'VTC0127';
		$list['topup_game']['tv_vtc'] = 'VTC0193';
		$list['topup_game']['asia_soft'] = 'VTC0051';
		$list['topup_game']['mcash'] = 'VTC0209';

		return array_get($list, $type.'.'.$type_type);
	}

	// --------------------------------------------------------------------

	/**
	 * Tao key ket noi cua VnptEpay
	 */
	protected function makeVnptEpayKeysConnection()
	{
		$products = t('db')->get($this->table)->result();

		foreach ($products as $product)
		{
			$provider = $this->getVnptEpayProviderKey($product->type_name, $product->type_type_name);

			if ( ! $provider) continue;

			$keys_connection = json_decode($product->keys_connection, true) ?: [];

			$keys_connection['VnptEpay'] = $provider.'_'.$product->type_value;

			$keys_connection = json_encode($keys_connection);

			t('db')->where('id', $product->id)
				   ->update($this->table, compact('keys_connection'));
		}
	}

	/**
	 * Lay key ket noi cua nha cung cap ben VnptEpay
	 *
	 * @param string $type
	 * @param string $type_type
	 * @return string
	 */
	protected function getVnptEpayProviderKey($type, $type_type)
	{
		$list = array();

		$list['code']['mobi'] = 'VMS';
		$list['code']['vina'] = 'VNP';
		$list['code']['viettel'] = 'VTT';
		$list['code']['gmobile'] = 'BEE';
		$list['code']['vnmobile'] = 'VNM';
		$list['code']['sfone'] = 'SFN';

		$list['code']['vcoin'] = 'VCOIN';
		$list['code']['gate'] = 'GATE';
		$list['code']['zingcard'] = 'ZING';
		$list['code']['oncash'] = 'ONCASH';
		$list['code']['megacard'] = 'MGC';
		$list['code']['garena'] = 'GARENA';
		$list['code']['zingxu'] = 'ZING';
		$list['code']['mobay'] = 'MOBAY';


		$list['topup_mobile']['mobi'] = 'VMS';
		$list['topup_mobile']['vina'] = 'VNP';
		$list['topup_mobile']['viettel'] = 'VTT';
		$list['topup_mobile']['gmobile'] = 'BEE';
		$list['topup_mobile']['vnmobile'] = 'VNM';
		$list['topup_mobile']['sfone'] = 'SFN';


		$list['topup_mobile_post']['mobi'] = 'VMS';
		$list['topup_mobile_post']['vina'] = 'VNP';
		$list['topup_mobile_post']['viettel'] = 'VTT';


		$list['topup_game']['vcoin'] = 'VCOIN';
		$list['topup_game']['gate'] = 'GATE';
		$list['topup_game']['zingcard'] = 'ZING';
		$list['topup_game']['oncash'] = 'ONCASH';
		$list['topup_game']['megacard'] = 'MGC';
		$list['topup_game']['garena'] = 'GARENA';
		$list['topup_game']['zingxu'] = 'ZING';
		$list['topup_game']['mobay'] = 'MOBAY';


		return array_get($list, $type.'.'.$type_type);
	}

}