
<!-- Common -->
<?php $this->load->view('admin/support_group/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<!-- Static table -->
	<div class="widget">
	
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_support_group'); ?></h6>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable myTable">
			<thead>
				<tr>
					<td style="width:70px;"><?php echo lang('sort_order'); ?></td>
					<td><?php echo lang('name'); ?></td>
					<td style="width:100px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
					<td class="textC"><?php echo $row->sort_order; ?></td>
					
					<td><?php echo $row->name; ?></td>
					
					<td class="option">
						<a href="<?php echo $row->_url_edit; ?>" class="lightbox tipS" title="<?php echo lang('edit'); ?>">
							<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
						</a>
						
						<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
							notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo $row->name; ?></b>">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
        