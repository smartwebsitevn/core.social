
<?php if (count($list)): ?>

	<div class="lang1">
		<?php foreach ($list as $row): ?>
		
			<a href="#" _url="<?php echo $row->_url_change; ?>" title="<?php echo $row->name; ?>" class="change_lang"
			><?php echo t('html')->img(public_url("site/images/langs/{$row->directory}.png")); ?></a>
		
		<?php endforeach; ?>
	</div>
	
<?php endif; ?>