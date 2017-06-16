<!-- Title area -->
<div class="titleArea">
	<div class="wrapper">
		<div class="pageTitle">
			<h5><?php echo lang('mod_site'); ?></h5>
			<span><?php echo lang('site_info'); ?></span>
		</div>

		<div class="horControlB menu_action">
			<ul>
				<li><a href="<?php echo admin_url('site'); ?>">
					<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/pencil.png" />
					<span><?php echo lang('mod_site_setting'); ?></span>
				</a></li>
				
				<li><a href="<?php echo admin_url('site/info'); ?>">
					<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/comment.png" />
					<span><?php echo lang('mod_site_info'); ?></span>
				</a></li>
				
				<li><a href="<?php echo admin_url('site/info_add'); ?>">
					<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/add.png" />
					<span><?php echo lang('mod_site_info_add'); ?></span>
				</a></li>
			</ul>
		</div>
		
		<div class="clear"></div>
	</div>
</div>
<div class="line"></div>


<!-- Breadcrumbs -->
<?php $this->widget->admin->breadcrumbs($breadcrumbs); ?>


<!-- Message -->
<?php if (isset($message)):?>
	<div class="wrapper">
		<?php $this->widget->admin->message($message); ?>
	</div>
<?php endif; ?>





