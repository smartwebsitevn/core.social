
<?php 
	t('lang')->load('site/deposit_card');

	$items = array(
		array(
			'url' 	=> site_url('tran/deposit'),
			'title' => lang('title_deposit_payment'),
		),
		array(
			'url' 	=> site_url('deposit_card'),
			'title' => lang('title_deposit_card'),
		),
		array(
			'url' 	=> site_url('deposit_bank/add'),
			'title' => lang('title_deposit_bank'),
		),
		array(
			'url' 	=> site_url('bank'),
			'title' => lang('title_deposit_bank_acc'),
		),
	);
?>

<div class="form-row">
	<?php foreach ($items as $item): ?>
		<label>
			<input type="radio"
				<?php echo form_set_checkbox($item['url'], current_url()); ?>
				onchange="window.parent.location = '<?php echo $item['url']; ?>';"
			/>
			<b><?php echo $item['title']; ?></b>
		</label>
	<?php endforeach; ?>
</div>
