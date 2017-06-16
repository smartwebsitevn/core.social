<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = lang('title_invoice_order_list');

	$table['filters'] = [

		[
			'param' => 'id',
			'value' => array_get($filter, 'id'),
		],
		[
                    'param'  => 'service_key',
                    'type'   => 'select',
                    'name'   => lang('type'),
                    'value'  => array_get($filter, 'service_key'),
                    'values' => array_pluck($services, 'name', 'key'),
                ],

		/*[
			'param'  => 'service_key_custom',
			'type'   => 'select',
			'name'   => lang('type'),
			'value'  => array_get($filter, 'service_key_custom'),
			'values' => ["Deposit" => "Nạp tiền","Order" => "Mua thẻ","Other" => "Hình thức khác"
			],
		],*/

		[
			'param' => 'key',
			'name'  => lang('key'),
			'value' => array_get($filter, 'key'),
		],

		[
			'param' => 'user_key',
			'name'  => lang('user'),
			'value' => array_get($filter, 'user_key'),
			'attr' => ['title' => 'Có thể nhập username, email, số điện thoại của thành viên'],
		],

		[
			'param' => 'user_ip',
			'name'  => 'IP',
			'value' => array_get($filter, 'user_ip'),
		],

		[
			'param'  => 'order_status',
			'type'   => 'select',
			'name'   => lang('order'),
			'value'  => array_get($filter, 'order_status'),
			'values' => lang_map($list_order_status, 'order_status_'),
		],

		[
			'param' => 'created',
			'type'  => 'date',
			'name'  => lang('from_date'),
			'value' => array_get($filter, 'created'),
		],

		[
			'param' => 'created_to',
			'type'  => 'date',
			'name'  => lang('to_date'),
			'value' => array_get($filter, 'created_to'),
		],

	];
	
	$table['columns'] = macro('tpl::invoice_order/macros')->make_columns();

	$table['rows'] = macro('tpl::invoice_order/macros')->make_rows($list);

	$table['stats'] = [
		'Tổng mệnh giá'  => $sums['format_amount_par'],
		'Tổng doanh thu' => $sums['format_amount'],
		'Tổng lãi'       => $sums['format_profit'],
	];

	echo macro()->page(array_merge($this->data, [
		'table' => $table,
		'toolbar' => [],

		'toolbar_addon' =>array(
			array(
				'url' => $url_export, 'title' => lang('button_export'), 'icon' => 'plus',
				'attr' => array('class' => 'btn btn-primary response_action',
					'notice' => lang('notice_confirm'),
					'_url' => $url_export,
				),
			),
		),
		'toolbar_sub' => array(
			array('url' => admin_url('invoice_order'), 'title' => 'Đơn hàng hoàn thành', 'attr' => $order_type == 'completed' ? array('class' => 'active') : ''),
			array('url' => admin_url('invoice_order/pending'), 'title' => 'Đơn hàng chờ xử lý', 'attr' => $order_type == 'pending' ? array('class' => 'active') : ''),
			//array('url' => admin_url('invoice_order/draf'), 'title' => 'Đơn hàng chưa xác nhận', 'attr' => $order_type == 'draf' ? array('class' => 'active') : ''),
			array('url' => admin_url('invoice_order/canceled'), 'title' => 'Đơn hàng hủy', 'attr' => $order_type == 'canceled' ? array('class' => 'active') : ''),
		),
	]));