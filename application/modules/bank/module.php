<?php namespace Modules\Bank;

class Module extends \MY_Module
{
	var $key = 'bank';

	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();

		$this->widget_set_config_bank($config);
		
		return $config;
	}
	
	/**
	 * Gan config bank
	 * 
	 * @param array $config
	 */
	protected function widget_set_config_bank(array &$config)
	{
		if (isset($config['list']['setting']['banks']))
		{
			$list = model('bank')->get_list();
			
			$config['list']['setting']['banks']['values'] = array_pluck($list, 'name', 'id');
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
		$data = array();

		$method = "widget_run_{$widget->widget}";
		if (method_exists($this, $method))
		{
			$data = $this->{$method}($widget);
		}
		
		return $data;
	}
	
	/**
	 * Xu ly widget list
	 */
	protected function widget_run_list($widget)
	{
		$filter = array();
		$filter['show'] = true;
		
		if ( ! empty($widget->setting['banks']))
		{
			$filter['id'] = $widget->setting['banks'];
		}
		
		$list = mod('bank')->get_list($filter);
		
		return compact('list');
	}
	
}
