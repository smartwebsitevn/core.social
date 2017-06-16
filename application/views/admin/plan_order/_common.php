<!-- Title area -->
<div class="titleArea">
	<div class="wrapper">
		<div class="pageTitle">
			<h5><?php echo $this->lang->line('mod_plan_order'); ?></h5>
			<span><?php echo $this->lang->line('plan_order_info'); ?></span>
		</div>
		
		<div class="horControlB menu_action">
			<ul>
				<li>
					<a href="<?php echo admin_url('plan_order'); ?>">
						<img src="<?php echo public_url('admin'); ?>/images/icons/control/16/list.png" />
						<span><?php echo $this->lang->line('list'); ?></span>
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





