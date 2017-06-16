<?php
$_row_data = function () use ($link) {
	ob_start(); ?>
	<div style="height:200px;max-width: 500px; word-wrap: break-word ; padding: 20px">
		<?php echo $link ?>
	</div>

	<?php return ob_get_clean();
};
echo  macro('mr::box')->box([
	'title'   => lang('title_test'),
	'content' => $_row_data(),
]);
?>

            
