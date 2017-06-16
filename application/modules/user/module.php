<?php namespace Modules\User;

use App\User\UserFactory;

class Module extends \MY_Module
{
	var $key = 'user';
	
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();
		
		t('load')->helper('user');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();

		$this->widget_set_config_menu($config);
		
		return $config;
	}
	
	/**
	 * Gan config menu
	 * 
	 * @param array $config
	 */
	protected function widget_set_config_menu(array &$config)
	{
		$menus = model('menu')->get_list();
		$menus = array_pluck($menus, 'name', 'key');

		if (isset($config['panel']['setting']['menu']))
		{
			$config['panel']['setting']['menu']['values'] = $menus;
		}

		if (isset($config['user_panel']['setting']['menu']))
		{
			$config['user_panel']['setting']['menu']['values'] = $menus;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Lay thong tin de hien thi widget
	 *
	 * @param object $widget	Thong tin widget
	 * @return array
	 */
	public function widget_run($widget)
	{
		t('lang')->load('site/user');

		return parent::widget_run($widget);
	}

	/**
	 * Xu ly widget panel
	 */
	protected function widget_run_panel($widget)
	{
		$is_login = UserFactory::auth()->logged();

		$user = UserFactory::auth()->user();
		
		$menu = $is_login ? mod('menu')->get($widget->setting['menu']) : [];
		
		return compact('is_login', 'user', 'menu');
	}

	/**
	 * Xu ly widget panel
	 */
	protected function widget_run_user_panel($widget)
	{
		$is_login = user_is_login();

		$user = user_get_account_info();
		$user = user_add_info($user);
		$user = mod_url('user', $user);

		$menu_items = t('mod')->menu->get_items($widget->setting['menu']);

		return compact('is_login', 'user', 'menu_items');
	}
	
}
