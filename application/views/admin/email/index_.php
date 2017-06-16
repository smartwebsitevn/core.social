
<?php echo macro()->page(['toolbar' => []]); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<!-- Static table -->
	<div class="widget">
	
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/frames.png" class="titleIcon" />
			<h6><?php echo lang('list'); ?> <?php echo lang('mod_email'); ?></h6>
		</div>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable sortTable">
			<thead>
				<tr>
					<td class="sortCol"><?php echo lang('key'); ?></td>
					<td class="sortCol"><?php echo lang('title'); ?></td>
					<td style="width:100px;"><?php echo lang('action'); ?></td>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
					<td><?php echo $row->key; ?></td>
					<td><?php echo $row->title; ?></td>
					<td class="option">
						<?php if ($row->_can_edit): ?>
							<a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS">
								<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
							</a>
						<?php endif; ?>
						
						<?php if ($row->_can_del): ?>
							<a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action hide" 
								notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo $row->title; ?></b>"
							>
								<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
        