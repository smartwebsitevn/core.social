<?php

/**
 * List
 */
$this->register('list', function($list)
{
	ob_start();?>

	<table class="table table-bordered table-hover">

		<thead>
		<th><?php echo lang('action'); ?></th>
		<th><?php echo lang('time'); ?></th>
		</thead>

		<tbody>

		<?php foreach ($list as $row): ?>

			<tr>
				<td><?php echo $row->action_name; ?></td>
				<td><?php echo $row->{'format:created,full'}; ?></td>
			</tr>

		<?php endforeach ?>

		</tbody>

	</table>

	<?php $body = ob_get_clean();

	return macro('mr::box')->box([
		'title'   => lang('title_list_log_activities'),
		'content' => $body,
	]);
});

