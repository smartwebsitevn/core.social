<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-info"></i> <?php echo lang('title_support_view'); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">
		<table class="table table-bordered table-striped table-hover tc-table">
			<tbody>
			<tr>
				<td class="row_label"><?php echo lang('id'); ?></td>
				<td class="row_item">
					<?php echo $info->id; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('name'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($info->name); ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('email'); ?></td>
				<td class="row_item">
					<?php echo $info->email; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('date'); ?></td>
				<td class="row_item">
					<?php echo $info->_created_full; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('subject'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($info->subject); ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<?php echo nl2br(htmlentities($info->message)); ?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
