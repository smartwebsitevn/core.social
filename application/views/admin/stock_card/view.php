<?php
	$mr = [];

	$mr['card'] = function() use ($card)
	{
		ob_start();?>

		<?php
			echo macro()->info([
				lang('product')     => t('html')->a(admin_url('stock_card') . '?product_id=' . $card->product_id, $card->product->name),
				lang('card_serial') => $card->serial,
				lang('card_code')   => $card->code,
				lang('card_expire') => $card->expire,
				lang('desc')        => $card->desc,
				lang('creator')     => t('html')->a($card->admin->{'adminUrl:view'}, $card->admin->username, ['target' => '_blank']),
				lang('status')      => macro()->status_color($card->sold ? 'off' : 'on', lang($card->sold ? 'sold' : 'unsold')),
				lang('created')     => $card->{'format:created,full'},
			]);
		?>

		<?php $body = ob_get_clean();

		return macro('mr::box')->box([
			'title'   => lang('title_stock_card_view'),
			'content' => $body,
		]);
	};

	echo macro()->page([
		'toolbar'  => macro('tpl::stock_card/macros')->toolbar(),
		'contents' => $mr['card'](),
	]);