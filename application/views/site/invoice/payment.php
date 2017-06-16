<?php
	$mr = [];

	$mr['order'] = function() use ($invoice)
	{
		ob_start();?>

		<table class="table table-bordered table-hover">

			<thead>
			<th><?php echo lang('type'); ?></th>
			<th><?php echo lang('desc'); ?></th>
			<th><?php echo lang('amount'); ?></th>
			</thead>

			<tbody>

			<?php foreach ($invoice->invoice_orders as $invoice_order): ?>

				<tr>
					<td><?php echo $invoice_order->service_name; ?></td>
					<td><?php echo implode('<br>', (array) $invoice_order->order_desc); ?></td>
					<td><?php echo $invoice_order->{'format:amount'}; ?></td>

				</tr>

			<?php endforeach ?>

			</tbody>

		</table>

		<?php return ob_get_clean();
	};

	$mr['payments'] = function() use ($payments)
	{
		ob_start();?>
		<?php widget('payment')->list_checkout($payments); ?>
	<?php /* ?>
		<div class="list-group">

			<?php foreach ($payments as $row): ?>

				<a href="<?php echo $row['url_pay']; ?>" class="list-group-item">

					<div class="pull-left">
						<b><?php echo $row['payment']->name; ?></b>
						<div><small><?php echo $row['payment']->desc; ?></small></div>
					</div>

					<div class="pull-right btn btn-default">
						<b><?php echo $row['format_amount']; ?></b>
					</div>

					<div class="clearfix"></div>
				</a>

			<?php endforeach ?>

		</div>
	<?php */?>
		<?php return ob_get_clean();
	};

	echo macro('mr::box')->box([
		'title'   => lang('title_invoice_order_view'),
		'content' => $mr['order'](),
	]);

	echo macro('mr::box')->box([
		'title'   => lang('title_payment_list'),
		'content' => $mr['payments'](),
	]);


