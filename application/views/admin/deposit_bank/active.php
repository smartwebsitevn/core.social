
<?php $_id = '_'.random_string('unique'); ?>

<script type="text/javascript"> 
(function($)
{
	$(document).ready(function()
	{
		var $main = $('.<?php echo $_id; ?>');
		
		$main.find('form').nstUI('formAction', {
			event_complete: function()
			{
				window.location.reload();
			},
		});
		
	});
})(jQuery);
</script>


<style>
#main_popup {
	min-height: 270px;
}
</style>


<div class="widget mg0 <?php echo $_id; ?>" id="main_popup">
	<div class="title">
		<img src="<?php echo public_url('admin'); ?>/images/icons/dark/list.png" class="titleIcon" />
		<h6><?php echo lang('title_deposit_bank_active'); ?></h6>
	</div>
	
	<form action="<?php echo $action; ?>" method="post" class="form">
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable table_info">
		<tbody>
			
			<tr>
				<td><?php echo lang('user'); ?></td>
				<td><?php echo t('html')->a(admin_url('user')."?id={$info->user_id}", $info->user->email, array('target' => 'target')); ?></td>
			</tr>
			
			<tr>
				<td><?php echo lang('transfer_amount'); ?></td>
				<td><?php echo $info->amount; ?></td>
			</tr>
			
			<?php 
				$_amount = trim($info->amount);
				$_amount = currency_handle_input($_amount);
				$_amount = $_amount ?: '';
			?>
			<tr>
				<td><?php echo lang('deposit_amount'); ?></td>
				<td>
					<?php echo t('html')->input('amount', $_amount); ?>
					<div name="amount_error" class="clear error"></div>
				</td>
			</tr>
			
			<tr>
				<td></td>
				<td>
					<input type="submit" value="<?php echo lang('accept'); ?>" class="blueB" />
				</td>
			</tr>
			
		</tbody>
		</table>
	</form>
</div>
