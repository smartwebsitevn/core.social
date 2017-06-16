<?php namespace Modules\Topup_offline;

class Module extends \MY_Module
{
	public $key = 'topup_offline';

	/**
	 * Lay config cua table
	 */
	public function table_get_config()
	{
		$config = parent::table_get_config();

		$this->table_set_config_user_group($config);
		
		return $config;
	}
	
	/**
	 * Gan config user_group
	 * 
	 * @param array $config
	 */
	protected function table_set_config_user_group(array &$config)
	{
		foreach (model('user_group')->get_list() as $row)
		{
			
			if (isset($config['providers_mobile']))
			{
				$config['providers_mobile']['cols']['discount_user_group_'.$row->id] = array(
					'type' 	=> 'text',
					'name' 	=> "Chiết khấu trả trước cho nhóm {$row->name}",
					'value' => '0',
					'show' => false,
				);
				
				$config['providers_mobile']['cols']['discount_post_user_group_'.$row->id] = array(
					'type' 	=> 'text',
					'name' 	=> "Chiết khấu trả sau cho nhóm {$row->name}",
					'value' => '0',
					'show' => false,
				);
			}
			
			if (isset($config['providers_game']))
			{
				$config['providers_game']['cols']['discount_user_group_'.$row->id] = array(
					'type' 	=> 'text',
					'name' 	=> "Chiết khấu cho nhóm {$row->name}",
					'value' => '0',
					'show' => false,
				);
			}
		}
	}
	
}
