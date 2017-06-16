<?php
	$mr = [];

	$mr['form'] = function() use ($action, $form, $cards)
	{
		ob_start();?>

		<form action="<?php echo $action ?>" method="post" class="form_action">

			<?php echo t('html')->hidden('product_id', $form->product_id); ?>
			<?php echo t('html')->hidden('desc', $form->desc); ?>

			<table class="table table-bordered table-hover">

				<thead>
					<th><?php echo lang('card_serial'); ?></th>
					<th><?php echo lang('card_code'); ?></th>
					<th><?php echo lang('card_expire'); ?></th>
				</thead>

				<tbody>

					<?php foreach ($cards as $i => $card): ?>

						<tr>
							<?php foreach (['serial', 'code', 'expire'] as $param): ?>

								<td>
									<?php echo t('html')->input(
										"cards[{$i}][{$param}]",
										$card[$param],
										['class' => 'form-control']
									); ?>
								</td>

							<?php endforeach; ?>
						</tr>

					<?php endforeach ?>

				</tbody>

				<tfoot>

					<tr>
						<td colspan="20">

							<div class="pull-left">
								<strong><?php echo lang('desc'); ?></strong>: <?php echo $form->desc ?>
								<div name="product_id_error" class="error"></div>
								<div name="cards_error" class="error"></div>
							</div>

							<?php echo t('html')->submit(lang('button_import_cards'), [
								'class' => 'btn btn-primary pull-right',
							]); ?>

						</td>
					</tr>

				</tfoot>

			</table>

		</form>

		<?php $body = ob_get_clean();

		return macro('mr::box')->box([
			'title'   => $form->product->name.' <small class="text-white">('.lang('total').': '.count($cards).')</small>',
			'content' => $body,
		]);
	};

	echo macro()->page([
		'toolbar'  => macro('tpl::stock_card/macros')->toolbar(),
		'contents' => $mr['form'](),
	]);
