
<?php //$content = html_entity_decode($widget->setting['content']); ?>
<?php $content = $widget->setting['content']; ?>

<?php if ( ! empty($widget->setting['box'])): ?>

	<div class="<?php echo $widget->setting['class']; ?>">
		<h2 class="bg-blue"><?php echo $widget->name; ?></h2>

		<div class="content">
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>
	</div>

<?php else: ?>

	<?php echo $content; ?>

<?php endif; ?>