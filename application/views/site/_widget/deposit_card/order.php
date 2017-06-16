

<div class="t-box" id="main_content">
	<div class="box-title">
		<h1><?php echo lang('title_tran_view'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<!-- Tran info -->
		<?php $this->load->view('tpl::_widget/tran/_info', array('tran' => $tran)); ?>
		
		
		<!-- Order info -->
		<div class="left order_info">
			<div class="left fontB f14 blue"><?php echo lang('title_order_info'); ?>:</div>
			<div class="clear"></div>
			
			<ul class="list2 valueB">
				<li><span><?php echo lang('card_type'); ?>:</span><?php echo $order->type; ?></li>
				<li><span><?php echo lang('card_code'); ?>:</span><?php echo $order->code; ?></li>
				<li><span><?php echo lang('card_serial'); ?>:</span><?php echo $order->serial; ?></li>
				<li><span><?php echo lang('card_amount'); ?>:</span><?php echo $order->_amount; ?></li>
				<li class="status">
					<span><?php echo lang('status'); ?>:</span>
					<font class="<?php echo $order->_status; ?>">
						<?php echo lang('status_'.$order->_status); ?>
					</font>
				</li>
			</ul>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</div>
