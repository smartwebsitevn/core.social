<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = $title;

	$table['filter'] = macro('tpl::invoice_order/macros')->make_filter($this->data);
	$table['columns'] = macro('tpl::invoice_order/macros')->make_columns();
	$table['rows'] = macro('tpl::invoice_order/macros')->make_rows($list);

	echo macro('mr::table')->table($table);