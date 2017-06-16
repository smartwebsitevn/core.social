<?php namespace Modules\Sayus;

class Module extends \MY_Module
{
	var $key = 'sayus';

	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();


		return $config;
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

		$list = model('sayus')->get_list($filter);
		foreach($list as $row){
			$row->image = file_get_image_from_name($row->image_name);
		}
		return compact('list');
	}
	
}
