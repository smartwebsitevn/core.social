<?php
use App\Invoice\InvoiceFactory;
use App\Invoice\Library\InvoiceStats;
class Order_report extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		//$this->lang->load('admin/'.$this->_get_mod());
	}

	/**
	 * Home
	 */
	public function index()
	{
		$filter = array();
		// loc
		$filter['cats'] = $this->input->get('cats');
		$filter['created'] = $this->input->get('created');
		$filter['created_to'] = $this->input->get('created_to');

		// thong ke theo san pham
		$services = [
				'ProductOrderCard',
				'ProductOrderTopupMobile',
				'ProductOrderTopupMobilePost',
				'ProductOrderTopupGame',
		];
		$where = array();
		if($filter['created']){
			$where['where']['invoice_order.created >='] = get_time_from_date($filter['created']);
		}
		if($filter['created_to']){
			$where['where']['invoice_order.created <='] = get_time_from_date($filter['created_to']);
		}
		$where['where']['?service_key'] = $services;
		$where['where']['product_id >'] = 0;
		$where['group'] = 'product_id';
		$where['select'] = 'service_key,sum(amount) as total_amount, sum(amount_par) as total_amount_par, sum(qty) as qty, product_id';
		//$where['join'] = array('product','product.id = invoice_order.product_id');
		$where['where']['order_status'] = 'completed';
		$product = model('invoice_order')->select($where);
		$this->data['product'] = array();
		foreach($product as $row){
			$this->data['product'][$row->product_id] = $row;
		}

		// lay danh sach danh muc, chi lam 2 cap
		$this->data['cats'] = array();
		$cats = model('cat')->get_list_level();
		$cats = $this->orderGetStast($cats);

		if($filter['cats'] > 0)
			$cats = array(model('cat')->_list_level_get_cat($cats, $filter['cats']));

		model('cat')->convert_list($cats, $this->data['cats']);


		$this->data['catslist'] = model('cat')->get();
		$this->data['filter'] = $filter;
		$this->_display();
	}

	private function orderGetStast($cats){
		foreach($cats as $row){
			$row->total_amount = $row->total_qty = 0;
			$where = array();
			$where['select'] = 'name,id';
			$where['where']['cat_id'] = $row->id;
			$row->_product = model('product')->select($where);
			foreach($row->_product as $pro){
				$pro->total_qty = $pro->total_amount = 0;
				if(isset($this->data['product'][$pro->id])){
					$pro->total_qty = $this->data['product'][$pro->id]->qty;
					$pro->total_amount = $this->data['product'][$pro->id]->total_amount;
					$row->total_amount += $pro->total_amount;
					$row->total_qty += $pro->total_qty;
				}
			}
			if($row->_sub){
				$row->_sub = $this->orderGetStast($row->_sub);
				foreach($row->_sub as $sub){
					$row->total_amount += $sub->total_amount;
					$row->total_qty += $sub->total_qty;
				}
			}
		}
		return $cats;
	}
	/**
	 * Tao filter
	 *
	 * @param array $input
	 * @return array
	 */
	protected function _make_filter(array $input)
	{
		$filter = [
			'type' => $this->_filter_type_default(),
		];

		if ($type = array_get($input, 'type'))
		{
			$type = explode('-', $type, 2);

			if ($type[0] == 'cat')
			{
				if (isset($type[1]))
				{
					$filter['cat_id'] = $type[1];
				}
			}
			else
			{
				$filter['type'] = $type[0];

				if (isset($type[1]))
				{
					$filter['type_type'] = $type[1];
				}
			}
		}

		if ($created = array_get($input, 'created'))
		{
			$created_to = array_get($input, 'created_to');

			$value = $created_to ? [$created, $created_to] : $created;

			$value = get_time_between($value);

			if ($value)
			{
			    $filter['created'] = $value;
			}
		}

		return $filter;
	}

	/**
	 * Lay filter type mac dinh
	 *
	 * @return array
	 */
	protected function _filter_type_default()
	{
		$types = ['topup_mobile', 'topup_game', 'topup_mobile_post'];

		foreach ($types as &$type)
		{
			$type = config('product_type_'.$type, 'main');
		}

		return $types;
	}

	/**
	 * Tao type options
	 *
	 * @param array $cats
	 * @param array $types
	 * @return array
	 */
	protected function _make_type_options(array $cats, array $types)
	{
		$result = [];

		$result['cat'] = lang('product_type_code');

		foreach ($cats as $cat)
		{
			foreach ($cat->_sub as $sub)
			{
				$result['cat-'.$sub->id] = ' + '.$sub->name;
			}

		}

		foreach ($types as $type)
		{
			if ($type['key'] == 'code') continue;

			$result[$type['id']] = lang('product_type_'.$type['key']);

			foreach ($type['type_types'] as $type_type)
			{
				$key = $type['id'].'-'.$type_type['id'];

				$result[$key] = ' + '.lang('name_'.$type_type['key']);
			}
		}

		return $result;
	}

}