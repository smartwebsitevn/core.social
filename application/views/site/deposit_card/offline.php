
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('#main_content');
		var $type = $main.find('[name=type]');
		var $amount = $main.find('[name=amount]');

		var form = {

			url_get: '<?php echo $url_get; ?>',

			request: function()
			{
				if ( ! $type.val() || ! $amount.val())
				{
					this.update();
					return;
				}

				$(this).nstUI('loadAjax', {
					url: this.url_get,
					data: $main.find('form').serialize(),
					field: {load: 'cart_request_load', show: ''},
					datatype: 'json',
					event_complete: function(data)
					{
						form.update(data);
					},
					event_error: function()
					{
						form.update();
					},
				});
			},

			update: function(data)
			{
				$main.find('[data-param=amount_discount]').html( data ? data['_amount_discount'] : '0' );
			},

			init: function()
			{
				$type.change(function()
				{
					form.request();
				});

				$amount.change(function()
				{
					form.request();
				});
			},
			
		};

		
		form.init();
		
	});
})(jQuery);
</script>


<div class="t-box" id="main_content">
	<div class="box-title">
		<h1><?php echo lang('title_deposit_card'); ?></h1>
	</div>
	
	<div class="box-content">
	
		<form class="t-form form_action" action="<?php echo $action; ?>" method="post">
			
			<div name="error_error" class="nNote nWarning hideit"></div>
			
			<?php 
				echo macro('mr::form')->row(array(
					'param' 	=> 'type',
					'type' 		=> 'select',
					'values' 	=> array_merge(array('' => ''), array_pluck($providers, 'name', '_id')),
					'req' 		=> true,
				));
				
				foreach (array('amount', 'code', 'serial') as $p)
				{
					echo macro('mr::form')->row(array(
						'param' => $p,
						'name' 	=> lang('card_'.$p),
						'req' 	=> true,
					));
				}
			?>
			
			<div class="form-row">
				<label class="form-label"><?php echo lang('amount_discount'); ?>:</label>
				<div class="form-item">
					<div class="fontB pt5 f14 red left" data-param="amount_discount">0</div>
					<div class="tab_load left mt5 ml10" id="cart_request_load"></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<?php echo macro('mr::form')->submit(); ?>
			
		</form>

		<div class="clear"></div>
	</div>
</div>
