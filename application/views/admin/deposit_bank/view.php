<?php
	$mr = [];

	$mr['actions'] = function() use ($order)
	{
		ob_start();?>

		<div class="btn-group btn-group-justified">

			<?php if ($order->{'can:complete'}): ?>
				<a href="" _url="<?php echo $order->{'adminUrl:complete'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('notice_confirm_action', ['action' => lang('button_complete')]); ?>"
					><?php echo lang('button_complete'); ?></a>

			<?php endif ?>

			<?php if ($order->{'can:cancel'}): ?>

				<a href="" _url="<?php echo $order->{'adminUrl:cancel'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('notice_confirm_action', ['action' => lang('button_cancel')]); ?>"
					><?php echo lang('button_cancel'); ?></a>

			<?php endif ?>

		</div>

		<?php return ob_get_clean();
	};

	$_data = function() use ($order, $mr)
	{

		//return array('type', 'bank', 'acc_name', 'acc', 'amount', 'date', 'desc');
		$invoice_order = $order->invoice_order;
		$detail =(object)$invoice_order->order_options;
		$body = macro()->info([
			lang('id')               => $invoice_order->id,
			lang('transfer_type')             => $detail->type,
			lang('transfer_bank')             => $detail->bank,
			lang('transfer_acc_name')             => $detail->acc_name,
			lang('transfer_acc')             => $detail->acc,
			lang('amount')           => $invoice_order->{'format:amount'},
			lang('transfer_desc') => $detail->desc,
			lang('order_status')     => macro()->status_color($order->status, lang('order_status_' . $order->status)),
			lang('user')             => t('html')->a($invoice_order->user->{'adminUrl:view'}, $invoice_order->user->name, ['target' => '_blank']),
			lang('created')          => $invoice_order->{'format:created, full'},
		]);

		$body .= $mr['actions']();

		return macro('mr::box')->box([
			'title'   => lang('title_deposit_bank'),
			'content' => $body,
		]);
	};

?>

<div class="row">

	<div class="col-md-12">

		<?php echo $_data(); ?>

	</div>

</div>