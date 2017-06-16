
<?php 
	$order = (array) $order;
	$order['orders'] = $order['topup_offline_order'];
	unset($order['status']);
	unset($order['_status']);
?>

<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('title_order_payment'); ?></h1>
	</div>
	
	<div class="box-content">

		<?php t('widget')->topup_offline->order_info(compact('order')); ?>
		<div class="clear"></div>
			
		<h3 class="h_title"><?php echo lang('title_payment_payment'); ?>:</h3>
		<?php $this->widget->payment->list_checkout($tran->id, $tran->amount, $tran->user_id); ?>
		
		<div class="clear"></div>
	</div>
</div>
