<?php
	echo macro('mr::table')->table([

		'title' => lang('title_tran_newest').' ['.t('html')->a($url_all, lang('button_view_all')).']',

		'columns' => macro('tpl::tran/macros')->make_columns(),

		'rows' => macro('tpl::tran/macros')->make_rows($list),

	]);
