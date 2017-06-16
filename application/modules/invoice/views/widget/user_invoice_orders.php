<?php

if ($user->id)
{
	echo macro('mr::box')->box([
		'title'   => $widget->name,
		'content' => macro('mr::table')->table([
			'columns'  => macro('tpl::invoice_order/macros')->make_columns(),
			'rows'     => macro('tpl::invoice_order/macros')->make_rows($list),
			'view_all' => count($list) ? $url_view_all : null,
		]),
	]);
}
