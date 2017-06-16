
<!-- Common -->
<?php $this->load->view('admin/site/_common'); ?>

<!-- Widget verify action -->
<?php $this->widget->admin->verify_action(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<!-- Static table -->
	<div class="widget">
	
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_site_info'); ?> <?php echo lang('mod_site'); ?></h6>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable myTable">
			<thead>
				<tr>
					<td class="sortCol"><?php echo lang('key'); ?></td>
					<td class="sortCol"><?php echo lang('value'); ?></td>
					<td style="width:70px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
					<td><?php echo $row->key; ?></td>
					
					<td><?php echo character_limiter(strip_tags($row->value), 50); ?></td>
					
					<td class="option">
						<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS">
							<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
						</a>
						
						<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action hide" 
							notice="<?php echo lang('notice_are_you_sure_want_to_delete'); ?>:<br><b><?php echo $row->key; ?></b>"
						>
							<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
