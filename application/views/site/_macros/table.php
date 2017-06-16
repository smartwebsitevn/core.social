<?php

/**
 * Table
 */
$this->register('table', function(array $args)
{
	$columns 	= $args['columns'];
	$rows 		= $args['rows'];
	$title 		= array_get($args, 'title');
	$total 		= array_get($args, 'total', count($rows));
	$filter 	= array_get($args, 'filter');
	$stats 		= array_get($args, 'stats');
	$orders 	= array_get($args, 'orders', []);
	$pages_config = array_get($args, 'pages_config');

	if ($title)
	{
		$title .= ' <small>('.lang('total').' '.number_format($total).')</small>';
	}

	ob_start();?>

	<?php if ($stats || $filter): ?>

		<div class="panel-body">

			<?php if ($stats) echo $this->macro->stats($stats); ?>

			<?php if ($filter) echo $this->macro->filter($filter); ?>

			<div class="clearfix"></div>
		</div>

	<?php endif; ?>

	<table class="table table-bordered table-hover mt20">

		<thead>
			<tr>
				<?php foreach ($columns as $label): ?>

					<th><?php echo $label ?></th>

				<?php endforeach ?>
			</tr>
		</thead>

		<tbody>

			<?php foreach ($rows as $row): ?>

				<tr>
					<?php foreach (array_keys($columns) as $col): ?>

						<td><?php echo array_get($row, $col) ?></td>

					<?php endforeach ?>
				</tr>

			<?php endforeach ?>

		</tbody>

	</table>

	<?php if ($pages_config): ?>

		<div class="panel-footer">

			<?php echo widget('site')->pages($pages_config) ?>

			<div class="clearfix"></div>
		</div>

	<?php endif ?>

	<?php $content = ob_get_clean();

	return $title ? macro('mr::box')->box(compact('title', 'content')) : $content;
});


/**
 * Filter
 */
$this->register('filter', function(array $args){ ob_start(); ?>

<?php
	$rows 	= $args['rows'];
	$data 	= array_get($args, 'data', []);
	$action = array_get($args, 'action', current_url());
?>

	<form action="<?php echo $action; ?>" method="get" class="form-filter">

		<?php
			foreach ($rows as $row)
			{
				if (is_string($row))
				{
					echo $row;
				}
				else
				{
					$row['value'] = array_get($row, 'value', array_get($data, $row['param']));

					echo $this->macro->filter_row($row);
				}
			}
		?>

		<div class="form-group">

			<button type="submit" class="btn btn-default btn-sm"
				><?php echo lang('button_search')?></button>

			<button type="reset" class="btn btn-default btn-sm"
				onclick="window.location.href = '<?php echo $action; ?>'"
				><?php echo lang('button_reset') ?></button>

		</div>

	</form>

<?php return ob_get_clean(); });


/**
 * Filter
 */
$this->register('filter_row', function(array $row){ ob_start(); ?>

	<?php
		$param 	= $row['param'];
		$name 	= array_get($row, 'name', lang($param));
		$type 	= array_get($row, 'type', 'text');
		$value 	= array_get($row, 'value');
		$values = array_get($row, 'values', []);
		$attr 	= array_get($row, 'attr', []);

		$make_field = function() use ($row, $type, $param, $value, $values, $attr)
		{
			$attr = array_merge(['class' => 'form-control'], $attr);

			switch ($type)
			{
				case 'text':
				{
					return t('html')->input($param, $value, $attr);
				}

				case 'select':
				{
					return t('html')->select($param, $values, $value, $attr);
				}

				case 'date':
				{
					$value = is_numeric($value) ? get_date($value) : $value;

					$attr['class'] .= ' datepicker';

					return t('html')->input($param, $value, $attr);
				}

				case 'custom':
				{
					return $row['html'];
				}
			}
		};
	?>

	<div class="form-group">

		<label><?php echo $name ?></label>

		<?php echo $make_field(); ?>

	</div>

<?php return ob_get_clean(); });


/**
 * Stats
 */
$this->register('stats', function(array $list)
{
	ob_start(); ?>

	<ul class="list-group">

		<?php foreach ($list as $label => $value): ?>

			<li class="list-group-item">

				<b><?php echo $label ?></b>

				<h4 class="pull-right text-danger" style="margin: 0;">
					<?php echo $value; ?>
				</h4>

				<div class="clearfix"></div>
			</li>

		<?php endforeach ?>

	</ul>

	<?php return ob_get_clean();
});


/**
 * Action
 */
$this->register('action', function (array $list){ ob_start(); ?>

	<?php
		$colors = [
			'install'     => 'success',
			'uninstall'   => 'danger',
			'setting'     => 'success',
			'set_default' => 'success',
			'translate'   => 'success',
			'view'        => 'success',
			'add'         => 'success',
			'edit'        => 'warning',
			'del'         => 'danger',
			'delete'      => 'danger',
		];

		foreach ($list as $act => $opt)
		{
			$url 		= array_get($opt, 'url');
			$confirm 	= array_get($opt, 'confirm', false);
			$notice 	= array_get($opt, 'notice', lang('notice_confirm_' . $act));
			$title 		= array_get($opt, 'title', lang('button_' . $act));
			$class 		= array_get($opt, 'class', '');
			$color 		= array_get($opt, 'color', array_get($colors, $act, 'success'));

			if ($confirm) $class .= ' verify_action';

			echo t('html')->a(
				$confirm ? '#' : $url,
				lang('button_' . $act),
				[
					'_url'   => $confirm ? $url : null,
					'notice' => $notice,
					'title'  => $title,
					'class'  => "btn btn-{$color} btn-xs {$class}",
				]
			);
		}
	?>

<?php return ob_get_clean(); });


/**
 * Tao actions cho data
 */
$this->register('actions_data', function ($data, array $actions)
{
	$list = [];

	foreach ($actions as $act => $opt)
	{
		if ( ! is_array($opt))
		{
			$act = $opt;
			$opt = [];
		}

		$opt['url'] = data_get($data, 'url:'.$act, data_get($data, '_url_'.$act));

		$list[$act] = $opt;
	}

	return $this->macro->action($list);
});