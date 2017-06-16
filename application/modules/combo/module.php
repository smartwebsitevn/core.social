<?php namespace Modules\Combo;

class Module extends \MY_Module
{
	var $key = 'combo';

	/**
	 * Lay config cua widget
	 * 
	 * @return array
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();
		
		return $config;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly widget list
	 */
	protected function widget_run_list($widget)
	{
		$filter = $this->make_filter_get_list($widget->setting);

		$input = $this->make_input_get_list($widget->setting);

		$list = $this->make_list($filter, $input);

		$url_more= $this->make_url_more($widget,$filter);
		return compact('list','url_more');
	}
	

	/**
	 * Tao filter dung trong get list
	 *
	 * @param array $args
	 * @return array
	 */
	protected function make_filter_get_list(array $args)
	{
		$filter['unexpire']=true;// chi lay cac combo con han
		foreach (array( 'feature', 'new') as $f) {
			if (isset($args[$f]) && !empty($args[$f])) {
				$filter[$f] = $args[$f] == 'yes' ? 1 : 0;
			}
		}

		foreach (array( 'product_id','image') as $f) {
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
				//'feature' => ['lesson.feature', 'desc'],
				'new' => ['combo.id', 'desc'],
				'name' => ['combo.name', 'asc'],
				'order' => [['combo.sort_order', 'asc'],['id','desc']],
				'random' => ['combo.id', 'random'],
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
		$list = model('combo')->filter_get_list($filter, $input);
		//pr_db($list);

		$list = $this->make_info($list);
		return $list;
	}

	/**
	 * Tao thong tin cho lesson
	 *
	 * @param array
	 * @return array
	 */
	protected function make_info(array $list)
	{
		foreach ($list as $row) {
			$row = mod('combo')->add_info($row);
		}
		return $list;
	}
	protected function make_url_more($widget,$filter)
	{
		// pr($widget->setting,0);
		//= su ly url more
		if(isset($widget->setting['url_more']) && $widget->setting['url_more'])
			$url_more = $widget->setting['url_more'];
		else
			$url_more = site_url('combo');

		if(isset($filter['show']))
			unset($filter['show']);
		if(count($filter)>0)
			$url_more  .='?' . url_build_query($filter);

		return $url_more;
	}
}
