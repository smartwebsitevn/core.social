<!-- Title area -->
<div class="titleArea">
	<div class="wrapper">
		<div class="pageTitle">
			<h5><?php echo $this->lang->line('mod_login_history'); ?></h5>
			<span><?php echo $this->lang->line('login_history_info'); ?></span>
		</div>
		
		<div class="horControlB menu_action">
			<ul>
				<li><a href="<?php echo admin_url('login_history/index/admin'); ?>">
					<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/admin.png" />
					<span><?php echo $this->lang->line('admin'); ?></span>
				</a></li>
				
				<li>
					<a href="<?php echo admin_url('login_history/index/user'); ?>">
						<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/list.png" />
						<span><?php echo $this->lang->line('user'); ?></span>
					</a>
				</li>
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





