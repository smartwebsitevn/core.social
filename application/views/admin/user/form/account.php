<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			var main = $('#form');

			// Chuy?n ??i cac field cua user_group
			main.find('select[name=user_group]').nstUI({
				method:	'toggleTab',
				toggleTab: {
					field: 'user_group'
				}
			});

			// Reset payment amount
			main.find('#reset_payment_amount').click(function()
			{
				var input = $(this).prev();
				input.val(input.attr('_value_default'));

				return false;
			});

		});
	})(jQuery);
</script>


	<?php
	$_data = function($data )
	{
		$user_groups = $data['user_groups'];
		$currency= $data['currency'];
		//pr($data);
		ob_start();?>
		<div class="formRight">
						<span class="oneTwo" style="width:auto;">
							<select name="user_group">
								<?php foreach ($user_groups as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php if ($row->_is_active) echo 'selected="selected"'; ?>>
										<?php echo $row->name; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</span>
			<span name="user_group_autocheck" class="autocheck"></span>
			<div name="user_group_error" class="clear error"></div>
		</div>

		<?php /* ?>
		<div class="formRight mt10">
						<span class="left fontB" style="width:160px;">
							<?php echo lang('payment'); ?>
						</span>

						<span class="left">
							<b><?php echo lang('payment_total_amount'); ?></b>
							<font class="red">(<?php echo $currency->code; ?>)</font>
						</span>
		</div>

		<div class="formRight">
			<div id="user_group">
				<?php foreach ($user_groups as $user_group): ?>
					<div _user_group="<?php echo $user_group->id; ?>">

						<?php foreach ($user_group->payments as $payment): ?>
							<?php $_id = 'payment_'.$user_group->id.'_'.$payment->code; ?>

							<div class="left mt5">
											<span class="left" style="width:160px; padding-top:3px;">
												<label for="<?php echo $_id; ?>">
													<?php echo $payment->name; ?>
												</label>
											</span>

											<span class="left link blue">
												<input name="payments[<?php echo $user_group->id; ?>][<?php echo $payment->code; ?>]" value="<?php echo $payment->amount; ?>" _value_default="<?php echo $payment->amount_default; ?>" id="<?php echo $_id; ?>" class="format_number" style="width:150px;" type="text" />
												<a href="" class="f11 ml5" id="reset_payment_amount">[<?php echo lang('reset')?>]</a>
											</span>
							</div>
							<div class="clear"></div>
						<?php endforeach; ?>

					</div>
					<div class="clear"></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php */ ?>

		<?php return ob_get_clean();
	};
	$info= isset($info) ? (array) $info : null;
	$_macro = $this->data;
	$_macro['form']['data'] =$info;

?>

	<?php
	//pr($info);
	echo macro('mr::form')->row(array(
		'param' =>  'name',
		'value' => $info[ 'name'],
		'req' 	=> true,
	));
	echo macro('mr::form')->row(array(
		'param' => 'username',
		'value' => $info['username'],
		'req' 	=> true,
	));
	echo macro('mr::form')->row(array(
		'param' =>  'phone',
		'value' => $info[ 'phone'],
		'req' 	=> true,
	));
	echo macro('mr::form')->row(array(
		'param' => 'email',
		'value' => $info['email'],
		'req' 	=> true,
	));

	echo '<hr class="separator">';
	echo macro('mr::form')->row(array(
		'param' => 'password',	'type' 	=> 'password',
	));

	echo macro('mr::form')->row(array(
		'param' => 'password_repeat','type' 	=> 'password',
	));
	echo macro('mr::form')->row(array(
		'param' => 'pin',
		'type' 	=> 'password',
	));

	echo macro('mr::form')->row(array(
		'param' => 'pin_repeat',
		'type' 	=> 'password',
	));

	echo '<hr class="separator">';


	echo macro('mr::form')->row(array(
		'param' => 'adsed',
		'values' => array('0' => lang('no'), '1' => lang('yes')),
		'value' => $info['adsed'],
		'type' => 'bool',
		'attr' => ["class" => "toggle_content tc"],

	));
	echo '<div id="adsed_content_1" class="adsed_content" style="display: none">';

	echo macro('mr::form')->row(array(
		'param' => 'adsed_begin', 'name' => lang('adsed_begin'), 'type' => 'date',
		'value' => $info['adsed_begin']?get_date($info['adsed_begin']):'',

	));
	echo macro('mr::form')->row(array(
		'param' => 'adsed_end', 'name' => lang('adsed_end'), 'type' => 'date',
		'value' => $info['adsed_end']?get_date($info['adsed_end']):'',

	));
	echo macro('mr::form')->row(array(
		'param' => 'adsed_order',
		'value' => $info['adsed_order']

	));
	echo '</div>';
	echo '<hr class="separator">';


	echo macro('mr::form')->row(array(
		'param' => 'is_feature',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'is_feature'],
	));

	echo macro('mr::form')->row(array(
		'param' => 'is_new',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'is_feature'],
	));
	echo macro('mr::form')->row(array(
		'param' => 'is_special',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'is_special'],
	));
	echo '<hr class="separator">';

	echo macro('mr::form')->row(array(
		'param' => 'activation',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'activation'],
	));

	echo macro('mr::form')->row(array(
		'param' => 'verify',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'verify'],
	));
	echo macro('mr::form')->row(array(
		'param' => 'blocked',
		'type' 	=> 'bool',
		'value'=> (int)$info[ 'blocked'],
	));

	echo '<hr class="separator">';
	echo macro('mr::form')->row(array(
		'param' =>  lang('user_group'),
		'type' 	=> 'ob',
		'value'=> $_data($this->data),
	));


	//	echo macro()->page($_macro);
	?>

