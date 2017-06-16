
<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('title_bank'); ?></h1>
	</div>
	
	<div class="box-content">
	
		<table cellpadding="0" cellspacing="0" width="100%" class="tDefault myTable">
		<thead>
			<tr>
				<?php /*?>
					<td><?php echo lang('image'); ?></td>
				<?php*/ ?>
				<td><?php echo lang('bank'); ?></td>
				<td><?php echo lang('acc_id'); ?></td>
				<td><?php echo lang('acc_name'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
				
					<?php /*?>
					<td>
						<a href="<?php echo $row->image->url; ?>" class="lightbox" target="_blank">
							<img src="<?php echo $row->image->url; ?>" style="height:50px;">
						</a>
					</td>
					<?php */?>
					
					<td><?php echo $row->name; ?></td>
					<td><?php echo $row->acc_id; ?></td>
					<td><?php echo $row->acc_name; ?></td>
				</tr>
			<?php endforeach; ?>
			
		</tbody></table>
		
		<div class="clear"></div>
	</div>
</div>
