<section class="main-right">
    <div class="container2">
<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = $title;

	$table['filter'] = macro('tpl::invoice/macros')->make_filter($this->data);
	$table['columns'] = macro('tpl::invoice/macros')->make_columns();
	$table['rows'] = macro('tpl::invoice/macros')->make_rows($list);

	echo macro('mr::table')->table($table);
	?>
	</div>
</section>