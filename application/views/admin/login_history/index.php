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


<!-- Common -->
<?php $this->load->view('admin/login_history/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<!-- Static table -->
	<div class="widget">
	
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo $this->lang->line('list_login_history_'.$type); ?></h6>
		</div>
		
		<form action="" method="get" class="form" name="filter">
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable">
			<thead>
				<tr>
					<td><?php echo $this->lang->line($type); ?></td>
					<td style="min-width:150px; width:15%;"><?php echo $this->lang->line('ip'); ?></td>
					<td style="min-width:150px; width:15%;"><?php echo $this->lang->line('time'); ?></td>
					<td style="min-width:50px; width:10%;"><?php echo $this->lang->line('action'); ?></td>
				</tr>
			</thead>
			
 			<tfoot>
				<tr>
					<td colspan="4">
						<?php $this->widget->admin->pages($pages_config); ?>
					</td>
				</tr>
			</tfoot>
			
			<tbody>
				<!-- Filter -->
				<tr>
					<td>
						<input name="user" value="<?php echo $filter['user']; ?>" type="hidden" />
						<input name="user_name" value="<?php echo $filter['user_name']; ?>" class="autocomplete" _url="<?php echo $url_search_user; ?>" type="text" style="width:95%;" />
					</td>
					<td><input name="ip" value="<?php echo $filter['ip']; ?>" type="text" style="width:95%;" /></td>
					<td><input name="time" value="<?php echo $filter['time']; ?>" class="datepicker" type="text" style="width:90%;" /></td>
					<td><input type="submit" class="button basic" value="<?php echo $this->lang->line('filter')?>" style="width:95%;" /></td>
				</tr>
				
				<!-- List -->
				<?php foreach ($list as $row): ?>
					<tr>
						<td>
							<a href="" class="view_of_field tipS" _param="user" _value="<?php echo $row->user_id; ?>" title="<?php echo $this->lang->line('view_of_user'); ?>:<br><?php echo $row->user_name; ?>">
								<?php echo $row->user_name; ?>
							</a>
						</td>
						
						<td>
							<a href="" class="view_of_field tipS" _param="ip" _value="<?php echo $row->ip; ?>" title="<?php echo $this->lang->line('view_of_ip'); ?>:<br><?php echo $row->ip; ?>">
								<?php echo $row->ip; ?>
							</a>
						</td>
						
						<td>
							<a href="" class="view_of_field tipS" _param="time" _value="<?php echo $row->_time; ?>" title="<?php echo $this->lang->line('view_of_time'); ?>:<br><?php echo $row->_time; ?>">
								<?php echo $row->_time_time; ?>
							</a>
						</td>
						
						<td class="option"></td>
						
					</tr>
				<?php endforeach; ?>
				
			</tbody>
		</table>
		</form>
		
	</div>
</div>
        