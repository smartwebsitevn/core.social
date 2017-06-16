
<?php if ( ! empty($widget->setting['box'])): ?>

	<div class="t-box box2">
		<div class="box-title">
			<h6><?php echo $widget->name; ?></h6>
		</div>
		
		<div class="box-content">
			
			<?php echo $widget->setting['content']; ?>
			
			<div class="clear"></div>
		</div>
	</div>
	
<?php else: ?>

	<?php echo $widget->setting['content']; ?>
	
<?php endif; ?>