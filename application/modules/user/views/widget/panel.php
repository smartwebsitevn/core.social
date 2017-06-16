<div class="top grid_9 text-right pull-right">
<div class="header-block">
					
<?php if ( ! $is_login): ?>

	<ul>
		<li>
			<b class="label"><div class="icon-16"><i class="fa fa-lock"></i></div></b>
			<a href="<?php echo $user->_url_login; ?>"
			><?php echo lang('button_login'); ?></a>
		</li>
		<li>
			<b class="label"><div class="icon-16"><i class="fa fa-user"></i></div></b>
			<a href="<?php echo $user->_url_register; ?>"
			><?php echo lang('button_register') ?></a>
		</li>
	</ul>

<?php else: ?>

	<ul>
		<li>
			<b class="label"><div class="icon-16"><i class="fa fa-user"></i></div></b>
			<a href="<?php echo $user->_url; ?>"
			><?php echo lang('hello'); ?>: <b><?php echo $user->name; ?></b></a>
		</li>
		<li>
			<b class="label"><div class="icon-16"><i class="fa fa-database"></i></div></b>
			<a href="<?php echo site_url('tran/deposit'); ?>"
			><?php echo lang('balance'); ?>: <b><?php echo $user->_balance; ?></b></a>
		</li>
		<li>
			<b class="label"><div class="icon-16"><i class="fa fa-lock"></i></div></b>
			<a href="<?php echo $user->_url_logout; ?>"
			><?php echo lang('button_logout'); ?></a>
		</li>
	</ul>

<?php endif; ?>

</div>
</div>