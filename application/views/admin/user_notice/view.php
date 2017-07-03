<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-info"></i> <?php echo lang('user_notice_detail'); ?></h4>
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
				<td class="row_label"><?php echo lang('title'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($info->title); ?>
				</td>
			</tr>


			<tr>
				<td class="row_label"><?php echo lang('date'); ?></td>
				<td class="row_item">
					<?php echo $info->_created_full; ?>
				</td>
			</tr>


			<tr>
				<td colspan="2">
					<b><?php echo lang('content'); ?></b><br>
					<?php echo nl2br(htmlentities($info->title)); ?>
					<?php echo nl2br(htmlentities($info->content)); ?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
