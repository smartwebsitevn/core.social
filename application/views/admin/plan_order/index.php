<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var form = $('form[name=filter]');
		
		form.find('.view_of_field').click(function()
		{
			var param = $(this).attr('_param');
			var value = $(this).attr('_value');
			
			form.find('input[name='+param+']').val(value);
			form.submit();
			
			return false;
		});
		
	});
})(jQuery);
</script>
<style>
.completed{
color:#006400;
}
</style>
<!-- Common -->
<?php $this->load->view('admin/plan_order/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

       <!-- Static table -->
	<div class="widget">
	<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo $this->lang->line('mod_plan_order'); ?></h6>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable">
	
		<thead>
			<tr>
				<td>Gói sử dụng</td>
				<td style="width:200px;">Số tiền</td>
				<td style="width:100px;">Thanh toán</td>

				<td style="width:100px;"><?php echo $this->lang->line('action'); ?></td>
			
			</tr>
		</thead>
		
		<tfoot class="auto_check_pages">
				<tr>
					<td colspan="9">
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
			</tfoot>
		<tbody>
		
		
		<tbody>
		<?php foreach ($list as $row): ?>
		    <?php $plan_info = $row->plan_info;?>
	
			<tr>
				<td class="link">
					<?php echo $plan_info->name; ?> (<?php echo $plan_info->day; ?> ngày)
				</td>
				
				<td >
				   <b class='req'><?php echo $plan_info->_cost_new; ?></b>
                   <?php if($plan_info->discount > 0){?><span style='text-decoration:line-through'><?php echo $plan_info->_cost; ?></span><?php }?>   
				</td>
				
				<td class="textC">
				<p class='<?php echo $row->_tran_status?>'><?php echo lang('tran_status_'.$row->_tran_status)?></p>
				</td>
                <td class="textC">
                <a href="<?php echo $row->_url_view; ?>" class="lightbox tipS" title="Chi tiết đơn hàng">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/view.png" />
							</a>
                </td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		
	</table>
	
</div>
</div>
