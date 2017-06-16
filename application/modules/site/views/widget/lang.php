
<?php if (count($list)): ?>

	<div class="lang1" style="position: absolute;top:5px; right: 100px;">
		<?php foreach ($list as $row): ?>
		
			<a style="float: left;margin-right:5px " href="<?php echo $row->_url_change; ?>" title="<?php echo $row->name; ?>"
			><?php echo t('html')->img(public_url('img/world/'.strtolower($row->code).'.gif')); ?>
			</a>
		
		<?php endforeach; ?>
	</div>
	
<?php endif; ?>
