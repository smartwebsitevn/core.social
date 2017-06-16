<?php
	$mr = [];

	$mr['actions'] = function() use ($tran)
	{
		ob_start();?>

		<div class="btn-group btn-group-justified">

			<?php if ($tran->{'can:active'}): ?>

				<a href="" _url="<?php echo $tran->{'adminUrl:active'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('notice_active_tran'); ?>"
				><?php echo lang('button_active'); ?></a>

			<?php endif ?>

			<?php if ($tran->{'can:cancel'}): ?>

				<a href="" _url="<?php echo $tran->{'adminUrl:cancel'}; ?>" class="btn btn-primary verify_action"
				   notice="<?php echo lang('notice_confirm_action', ['action' => lang('button_cancel')]); ?>"
				><?php echo lang('button_cancel'); ?></a>

			<?php endif ?>

		</div>

		<?php return ob_get_clean();
	};

	$mr['tran'] = function() use ($tran, $mr)
	{
		ob_start();?>

		<?php
			echo macro()->info([
				lang('id')              => $tran->id,
				lang('amount')          => $tran->{'format:amount'},
				lang('status')          => macro()->status_color($tran->status, lang('tran_status_'.$tran->status)),
				lang('payment')         => $tran->payment_id ? $tran->payment->name : '',
				lang('payment_tran_id') => $tran->payment_tran_id,
				lang('customer')        => $tran->user_id
					? t('html')->a($tran->user->{'adminUrl:view'}, $tran->user->name, ['target' => '_blank'])
					: lang('guest'),
				'IP'                    => t('html')->img(public_url('img/world/'.strtolower($tran->user_country_code).'.gif')).' '.$tran->user_ip,
				lang('created')         => $tran->{'format:created,full'},
			]);
		?>

		<?php echo $mr['actions'](); ?>

		<?php $body = ob_get_clean();

		return macro('mr::box')->box([
			'title'   => lang('title_tran_view'),
			'content' => $body,
		]);
	};

	$mr['payment_tran'] = function() use ($payment_tran)
	{
		return macro('mr::box')->box([
			'title'   => lang('title_payment_tran'),
			'content' => is_array($payment_tran) ? macro()->info($payment_tran) : $payment_tran,
		]);
	};

	$mr['page'] = function() use ($tran, $mr)
	{
		ob_start();?>

		<div class="row">

			<div class="col-md-6">

				<?php echo $mr['tran'](); ?>

			</div>

			<div class="col-md-6">

				<?php echo $mr['payment_tran'](); ?>

			</div>

		</div>

		<?php return ob_get_clean();
	};

	echo macro()->page([
		'toolbar'  => [],
		'contents' => $mr['page'](),
	]);