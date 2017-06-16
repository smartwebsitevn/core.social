<form method="get" action="<?php echo current_url() ?>" class="list_filter form">
	<div class="filter-wraper">
		<div class="filters">

			<div class="filter">

				<div class="input-group">
					<select class="form-control" name="cats">
						<option value=""><?php echo lang('all') ?></option>
						<?php foreach($catslist as $row){ ?>
						<option <?php if($filter['cats'] && $filter['cats'] == $row->id){?> selected<?php }?> value="<?php echo $row->id ?>"><?php echo ($row->_level > 1 ? '++ ' : '').$row->name ?></option>
						<?php } ?>
					</select>
					<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				</div>
			</div>

			<div class="filter">

				<div class="input-group">
					<input type="text" class="form-control text-right date_picker" value="<?php echo isset($filter['created']) ? $filter['created'] : '' ?>" name="created" placeholder="Từ ngày">
					<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				</div>
			</div>

			<div class="filter">

				<div class="input-group">
					<input type="text" class="form-control text-right date_picker" value="<?php echo isset($filter['created_to']) ? $filter['created_to'] : '' ?>" name="created_to" placeholder="Đến ngày">
					<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				</div>
			</div>
			<div class="filter act ">
				<button style="margin:0;" type="submit" class="btn btn-primary btn-small">Lọc</button>
				<button onclick="window.location.href = '<?php echo current_url()?>'; " type="reset" class="btn btn-small">Làm lại</button>
			</div>

		</div>
	</div>
</form>



<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4>
				<i class="fa fa-list-ul"></i>
				Báo cáo bán hàng
			</h4>
		</div>
		<div class="portlet-widgets">
			<a href="#_194338417b7320db3aff6b23b260df68" data-parent="#accordion" data-toggle="collapse"><i class="fa fa-chevron-down"></i></a>
			<span class="divider"></span>

		</div>
		<div class="clearfix"></div>
	</div>
	<div class="panel-collapse collapse in" id="_194338417b7320db3aff6b23b260df68">
		<div class="portlet-body no-padding">
			<table class="table table-bordered table-striped table-hover tc-table ">
				<thead>
				<tr>

					<th class=" column_id ">Loại</th>
					<th class=" column_service_key ">Số lượng</th>
					<th class=" column_desc ">Số tiền</th>
				</tr>
				</thead>

				<tbody class="list_item">
					<?php foreach($cats as $row) { ?>
						<tr class="lv<?php echo $row->_level ?>">
							<td><?php echo $row->name ?></td>
							<td><?php echo $row->total_qty ?></td>
							<td><?php echo currency_convert_format_amount($row->total_amount) ?></td>
						</tr>
						<?php if(isset($row->_product)) {
							foreach ($row->_product as $sub) { ?>
								<tr class="lv3">
									<td><?php echo $sub->name ?></td>
									<td><?php echo $sub->total_qty ?></td>
									<td><?php echo currency_convert_format_amount($sub->total_amount) ?></td>
								</tr>
							<?php }

						}
					}?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<style>

	.tc-table .lv1 {
		color: #3e76af;
		font-size: 16px;
		font-weight: bold;
	}

	.tc-table .lv2 {
		font-size: 14px;
		font-weight: bold;
	}
</style>