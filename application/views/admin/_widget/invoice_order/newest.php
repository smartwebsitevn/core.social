<?php
	echo macro('mr::table')->table([

		'title' => lang('title_invoice_order_newest').' ['.t('html')->a($url_all, lang('button_view_all')).']',

		'columns' => macro('tpl::invoice_order/macros')->make_columns(),

		'rows' => macro('tpl::invoice_order/macros')->make_rows($list),

	]);
