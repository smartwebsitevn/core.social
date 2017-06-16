
<div class="t-box">
	<div class="box-title">
		<h1>Danh sách thẻ nạp</h1>
	</div>
	
	<div class="box-content">
			
		<table cellpadding="0" cellspacing="0" width="100%" class="tDefault myTable">
			
			<thead>
				<tr>
					<td>Loại thẻ</td>
					<td>Mã thẻ</td>
					<td>Serial</td>
					<td>Mệnh giá</td>
					<td>Tiền (VNĐ)</td>
					<td>Trạng thái</td>
					<td>Ngày & Giờ</td>
				</tr>
			</thead>
			
			<tbody>
			
				<?php foreach ($list as $row): ?>
					<tr>
						<td><?php echo $row->type; ?></td>
						<td><?php echo $row->code; ?></td>
						<td><?php echo $row->serial; ?></td>
						<td><?php echo number_format($row->amount); ?></td>
						<td class="red"><?php echo number_format($row->amount_discount); ?></td>
						<td class="status">
							<font class="<?php echo $row->_status; ?>"
							><?php echo lang('status_'.$row->_status); ?></font>
						</td>
						<td><?php echo get_date($row->created, 'time'); ?></td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>
			
		</table>
	
		<div class="auto_check_pages">
			<?php widget('site')->pages($pages_config); ?>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
