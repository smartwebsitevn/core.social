
<div class="panel panel-default">
   <div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_order_checkout'); ?></h3>
	</div>
	
	<div class="panel-body"> 
	     <form action="<?php echo $action; ?>" class="t-form form_action" method="post">
		
			<?php t('widget')->topup_offline->order_info(compact('order')); ?>
			<div class="clear"></div>
			
			<?php
				if (empty($user))
				{
					view('tpl::topup_offline/_contact', compact('contact', 'captcha'));
				}
				else 
				{
					echo t('html')->submit(lang('button_payment'), array('class' => 'btn btn-primary right mt10'));
				}
			?>
			
		</form>
	</div>
</div>
