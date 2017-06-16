<?php
	$mr = [];

	$mr['actions'] = function() use ($withdraw)
	{
		ob_start();?>
        <?php $invoice = $withdraw->invoice?>
		<div class="btn-group btn-group-justified">

			<?php if ($withdraw->{'can:complete'} && $invoice->status == 'paid'): ?>

				<a href="" _url="<?php echo $withdraw->{'adminUrl:complete'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('help_complete_withdraw'); ?>"
					><?php echo lang('button_complete'); ?></a>

			<?php endif ?>



			<?php if ($withdraw->{'can:cancel'}): ?>

				<a href="" _url="<?php echo $withdraw->{'adminUrl:cancel'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('notice_confirm_action', ['action' => lang('button_cancel')]); ?>"
					><?php echo lang('button_cancel'); ?></a>

			<?php endif ?>

		</div>

		<?php return ob_get_clean();
	};

	$mr['withdraw'] = function() use ($withdraw, $mr)
	{
		$invoice_order = $withdraw->invoice_order;

		$body = macro()->info([
			lang('id')               => $invoice_order->id,
			lang('type')             => $invoice_order->service_name,
			lang('purse_number')     => $withdraw->purse->number,
			lang('withdraw_amount')  => $withdraw->{'format:amount'},
			lang('fee')              => $withdraw->{'format:fee'},
			lang('withdraw_payment') => $withdraw->payment->name,
			lang('receive_amount')   => "<b class='text-danger'>{$withdraw->{'format:receive_amount'}}</b>",
			lang('order_status')     => macro()->status_color($withdraw->status, lang('order_status_' . $withdraw->status)),
			lang('user')             => t('html')->a($withdraw->user->{'adminUrl:view'}, $withdraw->user->name, ['target' => '_blank']),
			lang('created')          => $withdraw->{'format:created,full'},
		]);

		$body .= $mr['actions']();

		return macro('mr::box')->box([
			'title'   => lang('title_invoice_order_view'),
			'content' => $body,
		]);
	};

	$mr['receiver'] = function() use ($receiver)
	{
		return macro('mr::box')->box([
			'title'   => lang('title_withdraw_receiver'),
			'content' => is_array($receiver) ? macro()->info($receiver) : $receiver,
		]);
	};

?>

<div class="row">

	<div class="col-md-6">

		<?php echo $mr['withdraw'](); ?>

	</div>

	<div class="col-md-6">

		<?php echo $mr['receiver'](); ?>

		<?php echo macro('tpl::log_activity/macros')->list($log_activities); ?>

	</div>

</div>