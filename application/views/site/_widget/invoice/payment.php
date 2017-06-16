<style>
	#main_content ul.list2 li {
		margin: 6px 0px;
	}
	#main_content ul.list2 li span {
		width: 90px;
	}
</style>


<div class="t-box" id="main_content">
	<div class="box-title">
		<h1><?php echo lang('title_cart_payment'); ?></h1>
	</div>

	<div class="box-content">

		<!-- Thong tin don hang -->
		<?php  foreach($invoices as $invoice): //pr($invoice); ?>
			<?php widget($invoice->_type)->invoice($invoice->id); ?>
		<?php endforeach; ?>
		<div class="clear"></div>

		<!-- Thanh toan don hang -->
		<div class="h_title"><?php echo lang('title_payment_payment'); ?>:</div>
		<div class="clear"></div>

		<?php $this->widget->payment->list_checkout($tran->id, $tran->amount, $tran->user_id); ?>

		<div class="clear"></div>
	</div>
</div>
