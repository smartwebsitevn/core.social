<div role="tabpanel" class="tab-pane" id="special">

<table id="special-table" class="table table-bordered">
	<thead>
	<tr>
		<th>Nhóm khách hàng</th>
		<th>Khuyến mại(số tiền này sẽ được trừ vào giá sản phẩm)</th>
		<th>Từ ngày</th>
		<th>Đến ngày</th>
		<th>Sắp xếp</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?php

	$special=isset($info['special'])?$info['special']:null;
	if(! empty($special) )
		foreach ($special as $row) 
		{
			?>
				<tr>
					<td>
						<select name="special[<?php echo $row->id ?>][customer_group_id]">
							<option value="0">default</option>
						</select>
					</td>
					<td>
						<input type="text" class="form-control input_number" name="special[<?php echo $row->id ?>][price]" value="<?php echo $row->price ?>" />
					</td>
					<td>
						<input type="text" class="date_picker mask_datess" name="special[<?php echo $row->id ?>][begin_date]" value="<?php echo $row->begin_date ? date('d-m-Y',$row->begin_date) : '' ?>">
					</td>
					<td>
						<input type="text" class="date_picker mask_datess" name="special[<?php echo $row->id ?>][end_date]" value="<?php echo $row->end_date ? date('d-m-Y',$row->end_date) : '' ?>">
					</td>
					<td>
						<input type="number" name="special[<?php echo $row->id ?>][sort]" value="<?php echo $row->sort ?>" />
					</td>
					<td>
						<a href="javascript:;" class="btn btn-danger" onclick="$(this).parent().parent().remove();">
							<i class="fa fa-minus-circle"></i>
						</a>
					</td>
				</tr>
			<?php
		}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><a class="btn btn-primary special-add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
	</tr>
	</tfoot>
</table>
	</div>