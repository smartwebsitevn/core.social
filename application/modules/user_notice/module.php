<?php namespace Modules\User_notice;

class Module extends \MY_Module
{
	var $key = 'user_notice';

	/**
	 * Lay setting cua module
	 */
	public function setting($module)
	{
		if (isset($module->setting['url_more']))
			$module->setting['url_more'] = handle_content($module->setting['url_more'], 'output');
	}

	/**
	 * Ham duoc goi truoc khi luu thong tin module
	 * @param object $module Thong tin module
	 */
	public function setting_save_pre($module)
	{

		if (isset($module->setting['url_more']))
			$module->setting['url_more'] = handle_content($module->setting['url_more'], 'input');
	}
	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();

		$this->widget_set_config($config);

		return $config;
	}
	/**
	 * Gan config cat
	 *
	 * @param array $config
	 */
	protected function widget_set_config(array &$config)
	{
		t('lang')->load('admin/user_notice');
		$ids = array_pluck(model('user_notice')->get_list(), 'name', 'id');
		$cat_ids = array_pluck(model('user_notice_cat')->get_hierarchy_data(), '_name', 'id');

		foreach (array('list', 'list_fixed') as $type) {
			foreach (array('', 1,2,3) as $k) {
				if (isset($config[$type]['setting']['id' . $k])) {
					$config[$type]['setting']['id'. $k]['values'] = $ids;
				}
				if (isset($config[$type]['setting']['layout_id' . $k])) {
					$config[$type]['setting']['layout_id'. $k]['values'] = $ids;
				}
				if (isset($config[$type]['setting']['cat_id' . $k])) {
					$config[$type]['setting']['cat_id'. $k]['values'] = $cat_ids;
				}
				if (isset($config[$type]['setting']['layout_cat_id' . $k])) {
					$config[$type]['setting']['layout_cat_id'. $k]['values'] = $cat_ids;
				}
			}
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
		t("lang")->load("site/user_notice");
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
		if (isset($widget->setting['id']) && $widget->setting['id']) {
			$filter = ['id' => $widget->setting['id']];
			$filter = $this->make_filter_get_list($filter);
		} else
			$filter = $this->make_filter_get_list($widget->setting);
		$input = $this->make_input_get_list($widget->setting);
		$list = $this->make_list($filter, $input);
		$list_feature=[];
		if(isset($widget->setting['layout_id']) && $widget->setting['layout_id'])
			$list_feature =$this->make_list(["ids"=>$widget->setting['layout_id'],'show'=>1]);
		$url_more= $this->make_url_more($widget,$filter);
		return compact('list','list_feature','url_more');
	}
	/**
	 * Xu ly widget list fixed
	 */
	protected function widget_run_list_fixed($widget)
	{
		$list1 =$this->make_list(["ids"=>$widget->setting['id1'],'show'=>1]);
		$list2 =$this->make_list(["ids"=>$widget->setting['id2'],'show'=>1]);
		$list3 =$this->make_list(["ids"=>$widget->setting['id3'],'show'=>1]);
		//$url_more= $this->make_url_more($widget,$filter);
		//pr($url_more,0);
		return compact('list1','list2','list3');
	}

	/**
	 * Tao filter dung trong get list
	 *
	 * @param array $args
	 * @return array
	 */
	protected function make_filter_get_list(array $args)
	{

		$filter = ['show' => 1];

		if (isset($args['price'])) {
			switch ($args['price']) {
				case 0:
					$filter['price'] = $args['price'];
					break;
				case 1:
					$filter['price_gt'] = 0;
					break;
			}
		}



		foreach (array( 'feature', 'new', 'soon') as $f) {
			if (isset($args[$f]) && !empty($args[$f])) {
				$filter['is_'.$f] = $args[$f] == 'yes' ? 1 : 0;
			}
		}

		foreach (array( 'id','cat_id', 'image','banner','icon') as $f) {
			if (isset($args[$f]) && !empty($args[$f])) {
				$filter[$f] = $args[$f];
			}
		}
		//pr($args,0);
		// pr($filter);
		return $filter;
	}

	/**
	 * Tao input dung trong get list
	 *
	 * @param array $args
	 * @return array
	 */
	protected function make_input_get_list(array $args)
	{
		$input = [];
		if (isset($args['order'])) {
			$orders = [
				'new' => ['id', 'desc'],
				'feature' => ['is_feature', 'desc'],
				'name' => ['name', 'asc'],
				'rate' => ['rate', 'desc'],
				'view' => ['count_view', 'desc'],
				'buy' => ['count_buy', 'desc'],
				'updated' => ['updated', 'desc'],
				'random' => ['id', 'random'],
			];

			$order = $args['order'];

			if (isset($orders[$order])) {
				$input['order'] = $orders[$order];
			}
		}

		if (isset($args['total'])) {
			$total = max(0, (int)$args['total']);

			$input['limit'] = array(0, $total);
		}

		return $input;
	}

	/**
	 * Lay danh sach
	 *
	 * @param array $filter
	 * @param array $input
	 * @return \TF\Support\Collection
	 */
	protected function make_list(array $filter, array $input = [])
	{
		$list = model('user_notice')->filter_get_list($filter, $input);
		//pr_db($list);
		$list = $this->make_info($list);
		return $list;
	}

	/**
	 * Tao thong tin cho user_notice
	 *
	 * @param array
	 * @return array
	 */
	protected function make_info(array $list)
	{
		foreach ($list as $row) {
			$row = mod('user_notice')->add_info($row);
		}
		return $list;
	}
	protected function make_url_more($widget,$filter)
	{
		//pr($filter);
		// pr($widget->setting,0);
		//= su ly url more
		if(isset($widget->setting['url_more']) && $widget->setting['url_more']){
			$url_more = $widget->setting['url_more'];
			$url_more = handle_content($url_more, 'output');

		}
		else
			$url_more = site_url('user_notice_list');
		if(isset($filter['show']))
			unset($filter['show']);
		if(count($filter)>0)
			$url_more  .='?' . url_build_query($filter);
		//pr($url_more,0);

		return $url_more;
	}
}
