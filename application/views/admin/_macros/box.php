<?php

/**
 * Box
 */
$this->register('box', function(array $args){ ob_start(); ?>

<?php
	$title 		= array_get($args, 'title');
	$body 		= array_get($args, 'body');
	$content 	= array_get($args, 'content');
	$icon 		= array_get($args, 'icon');
	$color		= array_get($args, 'color'); // default, primary, success, info, warning, danger

	$class = $color ? 'bg-'.$color : 'bg-primary';
?>

	<div class="portlet">

		<?php if ($title): ?>

			<div class="portlet-heading <?php echo $class; ?>">

				<div class="portlet-title">
					<h4>
						<?php if ($icon) echo '<i class="fa fa-'.$icon.'"></i>'; ?>
						<?php echo $title; ?>
					</h4>
				</div>

				<div class="clearfix"></div>
			</div>

		<?php endif; ?>

		<div class="panel-collapse collapse in">

			<?php if ($body): ?>

				<div class="portlet-body"><?php echo $body; ?></div>

			<?php endif ?>

			<?php if ($content): ?>

				<div class="portlet-body no-padding"><?php echo $content; ?></div>

			<?php endif ?>

		</div>

	</div>

<?php return ob_get_clean(); });