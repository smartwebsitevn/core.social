
<!-- Common -->
<?php $this->load->view('admin/coupon/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<!-- Static table -->
	<div class="widget">
	
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_coupon'); ?></h6>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable myTable">
			<thead>
				<tr>
				   
					<td class="sortCol"><?php echo lang('name'); ?></td>
					<td class="sortCol"><?php echo lang('code'); ?></td>
					<td class="sortCol"><?php echo lang('discount'); ?></td>
					<td class="sortCol"><?php echo lang('number_user'); ?></td>
					<td class="sortCol"><?php echo lang('number_usered'); ?></td>
					<td class="sortCol"><?php echo lang('status'); ?></td>
					<td class="sortCol"><?php echo lang('expire'); ?></td>
					<td style="width:100px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
				    
					<td>
						<?php echo $row->name; ?> 
					</td>
					
					<td><?php echo $row->code; ?></td>
					
					<td><?php echo $row->_discount; ?></td>
					
					
					
					<td><?php echo $row->number_user; ?></td>
					
					<td><?php echo $row->number_usered; ?></td>
					
					<td><?php echo $row->_status; ?></td>
					
					<td><?php echo $row->_expire; ?></td>
					
					<td class="option">
						
						<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS">
							<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
						</a>
						
						<a href="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
							notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:
								<br><strong><?php echo $row->name; ?></strong>
							">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
						</a>
						
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
        