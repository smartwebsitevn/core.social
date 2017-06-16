<?php namespace Modules\Deposit_sms;

class Module extends \MY_Module
{
	public $key = 'deposit_sms';

	/**
	 * Lay config cua setting
	 */
	public function setting_get_config()
	{
		$config = parent::setting_get_config();

		$this->setting_set_config_user_group($config);
		
		return $config;
	}
	
	/**
	 * Gan config user_group
	 * 
	 * @param array $config
	 */
	protected function setting_set_config_user_group(array &$config)
	{
		foreach (model('user_group')->get_list() as $row)
		{
			$config['fee_percent_user_group_'.$row->id] = array(
				'type' 	=> 'text',
				'name' 	=> "Phí chiết khấu cho nhóm {$row->name} (%)",
				'value' => '',
				'desc' => 'Nếu không khai báo thì lấy theo Phí chiết khấu mặc định',
			);
		}
	}
	
	/**
	 * Gan config amounts
	 * 
	 * @param array $config
	 */
	protected function setting_set_config_amounts(array &$config)
	{
		$ports = mod('sms')->config('mods');
		$ports = array_get($ports, 'deposit.1');
		
		foreach ((array) $ports as $port)
		{
			$num = substr($port, 1, 1);
			
			$config['amount_'.$num] = array(
				'type' 	=> 'text',
				'name' 	=> "Số tiền nạp ứng với đầu số {$port}",
				'desc' 	=> "Số tiền nạp cho thành viên khi nhắn tin đến đầu số {$port}",
				'value' => '',
			);
		}
	}

}
