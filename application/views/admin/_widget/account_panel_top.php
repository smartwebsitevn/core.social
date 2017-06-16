
<div class="topNav">
	<div class="wrapper">
		<div class="welcome">
			<span><?php echo lang('hello')?>: <b><?php echo $acount->username; ?>!</b></span>
			<span>IP: <b><?php echo $this->input->ip_address(); ?></b></span>
			<span>Time: <b><?php echo format_date(now(), 'full'); ?></b></span>
		</div>
		
		<div class="userNav">
			<ul>
				<li><a href="<?php echo site_url(); ?>" target="_blank">
					<img style="margin-top:7px;" src="<?php echo public_url('admin'); ?>/images/icons/light/home.png" />
					<span><?php echo lang('home_page')?></span>
				</a></li>
				
				<!-- Logout -->
				<li><a href="<?php echo admin_url('home/logout'); ?>">
					<img src="<?php echo public_url('admin'); ?>/images/icons/topnav/logout.png" alt="" />
					<span><?php echo lang('button_logout')?></span>
				</a></li>
				
			</ul>
		</div>
		
		<div class="clear"></div>
	</div>
</div>
