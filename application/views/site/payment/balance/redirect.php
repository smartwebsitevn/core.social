<div class="t-box" style="min-width: 500px; min-height: 350px;">
	<div class="box-title">
		<h1><?php echo lang('payment_balance_notice'); ?></h1>
	</div>
	
	<div class="box-content">

		<form class="t-form form_action" action="<?php echo $action; ?>" method="post">
			
			
			<?php
				if ( ! $can_payment)
				{
					widget('site')->message('info', lang('notice_balance_not_enough'));
				}
			?>
			
			
			<div class="form-row row-text">
				<label class="form-label"><?php echo lang('balance_current'); ?>:</label>
			
				<div class="form-item item-text fontB f13">
					<?php echo $balance['_current']; ?>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row row-text">
				<label class="form-label"><?php echo lang('amount_payment'); ?>:</label>
			
				<div class="form-item item-text fontB f13">
					<?php echo $balance['_payment']; ?>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row row-text">
				<label class="form-label"><?php echo lang('balance_remaining'); ?>:</label>
			
				<div class="form-item item-text fontB f13">
					<?php echo $balance['_remaining']; ?>
				</div>
				<div class="clear"></div>
			</div>
			
			
			<?php
				if ($can_payment)
				{
					echo mod('user_security')->form();
				
					echo macro('mr::form')->submit(lang('button_payment'));
				}
			?>
			
			
		</form>
	
		<div class="clear"></div>
	</div>
</div>

<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			$('.form_action').each(function()
			{
				var $this = $(this);
				$this.nstUI('formAction', {
					field_load: $this.attr('_field_load'),

				});
			});

		});
	})(jQuery);
</script>

<?php /*$_id = '_'.random_string('unique'); ?>
	
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#<?php echo $_id; ?>');
		
		main.find('#act_payment').click(function()
		{
			$(this).nstUI('loadAjax', {
				url: $(this).attr('href'),
				event_complete: function()
				{
					window.parent.location = '<?php echo $url_tran; ?>';
				}
			});
			
			return false;
		});
		
	});
})(jQuery);
</script>


<style>
#payment_balance {
	width: 600px;
}
#payment_balance #message-box {
	margin-bottom: 10px;
}
</style>


<div class="t-box payment_banking" id="payment_balance">
	<div class="box-title">
		<h1><?php echo lang('payment_balance_notice'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<?php
			if (!$can_payment)
			{
				$message = array('info', lang('notice_your_balance_is_not_enough_to_pay'));
				$this->widget->site->message($message);
			}
		?>
	
		<div class="t-form">
			<div class="form-row">
				<label class="form-label"><?php echo lang('user_balance_cur'); ?>:</label>
				<div class="form-item f16 blue fontB">
					<?php echo $balance->user; ?>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label"><?php echo lang('amount_payment'); ?>:</label>
				<div class="form-item f20 red fontB">
					<?php echo $balance->cost; ?>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label"><?php echo lang('balance_remaining'); ?>:</label>
				<div class="form-item f16 green fontB">
					<?php echo $balance->remaining; ?>
				</div>
				<div class="clear"></div>
			</div>
		
			<?php if ($can_payment): ?>
				<div class="form-row">
					<label class="form-label">&nbsp;</label>
					<div class="form-item">
						<a id="act_payment" class="button button-border medium red f" href="<?php echo $url_payment; ?>">
							<i class="icon-share-alt icon-white"></i><font><?php echo lang('button_payment'); ?></font>
						</a>
			 		</div>
				</div>
			<?php endif; ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear pb5"></div>
		<div class="left red fontB textC" style="width:100%;"><?php echo lang('note_payment_balance'); ?></div>
			
		<div class="clear"></div>
	</div>
</div>
<?php */?>
