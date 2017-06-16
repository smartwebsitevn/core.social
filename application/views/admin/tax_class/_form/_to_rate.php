</div>
</div>
<div><div>

<table id="_to_rate-table" class="table table-bordered">
	<tr>
		<th>Mức thuế</th>
		<th>Ưu tiên</th>
		<th></th>
	</tr>
	<?php
		if(! empty($_to_rate) )
		foreach ($_to_rate as $row) 
		{
			?>
				<tr>
					<td>
						<select name="_to_rate[<?php echo $row->id ?>][rate_id]">
							<option value="0"> - - Chưa chọn - - </option>
							<?php 
								foreach($rates as $item)
								{
									?>
									<option value="<?php echo $item->id ?>" <?php echo $item->id == $row->rate_id ? 'selected' : '' ?>><?php echo $item->name ?></option>
									<?php
								}
							?>
						</select>
					</td>
					<td>
						<input type="number" name="_to_rate[<?php echo $row->id ?>][piority]" value="<?php echo $row->piority ?>" />
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
	<tr>
		<td></td>
		<td></td>
		<td><a class="btn btn-primary _to_rate-add" href="javascript:;"><i class="fa fa-plus-circle"></i></a></td>
	</tr>
</table>