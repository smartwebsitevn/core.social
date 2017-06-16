 <style>
.tab-content{padding:20px;}
.col-25{display:inline-block;width:25%}
.col-50{display:inline-block;width:50%}
.col-20{display:inline-block;width:18%}
.col-10{display:inline-block;width:10%}
.tablerow{margin-top:10px}
.tablerow select{width:120px}
.thead span{font-weight:bold;}
.list2 li span{display:inline-block;width:150px}
.list2 li{margin-bottom:20px !important;}
</style>
 
<div class="panel panel-default">
   <div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_topup_offline'); ?></h3>
	</div>
	<div class="panel-body">  
        <div class="form-table">
        	<div class="thead">
        		<span class="col-25"><?php echo lang('topup_account'); ?></span>
        		<span class="col-25"><?php echo lang('topup_amount'); ?></span>
        		<span class="col-20"><?php echo lang('topup_provider'); ?></span>
        		<span class="col-20"><?php echo lang('topup_type'); ?></span>
        	</div>
        	
        	<?php $order_options = $topup_offline->invoice_order->order_options?>
        	<?php foreach ($order_options as $row): ?>
        		<?php $row = (array) $row?>
        	    <?php if(!isset($row['account'])) return;?>
        		<div class="tablerow">
        			<span class="col-25 text">
        				<?php echo $row['account']; ?>
        			</span>
        			
        			<span class="col-25 text">
        				<?php echo currency_format_amount($row['amount_total']); ?>
        			</span>
        			
        			<span class="col-20 text">
        				<?php echo $row['provider']; ?>
        			</span>
        			
        			<span class="col-20 text">
        				<?php echo lang('topup_type_'.$row['type']); ?>
        			</span>
        		
        		</div>
        	<?php endforeach; ?>
        	
        	<div class="tablerow pt20">
        		<ul class="list2 order_amounts">	
        			<li>
        				<span><?php echo lang('amount_total'); ?>:</span>
        				<font class="blue"><?php echo $topup_offline->_amount_total ?></font>
        			</li>
        			
        			<li>
        				<span><?php echo lang('amount_discount'); ?>:</span>
        				<font class="blue"><?php echo $topup_offline->_amount_discount ?></font>
        			</li>
        			
        			<li>
        				<span><?php echo lang('amount_payment'); ?>:</span>
        				<font class="red"><?php echo $topup_offline->_amount ?></font>
        			</li>
        			
        		
    				<li class="status">
    					<span><?php echo lang('status'); ?>:</span>
    					<font class="<?php echo $topup_offline->status ?>">
    						<?php echo macro()->status_color(mod('order')->status_name($topup_offline->status), lang('order_status_' . mod('order')->status_name($topup_offline->status))) ?>
    					</font>
    					<div class="clear"></div>
    				</li>
    				
    				<li class="status">
    					<span><?php echo lang('action'); ?>:</span>
    					 <?php if($topup_offline->status != mod('order')->status('completed')):?>
    					<a href="" _url="<?php echo $topup_offline->_url_active; ?>"
    					   title="<?php echo lang('notice_are_you_sure_want_to_active'); ?>"
    					   class="btn btn-danger btn-xs verify_action"
    					   notice="<?php echo lang('notice_are_you_sure_want_to_active'); ?>:<br><strong><?php echo  $topup_offline->id; ?></strong>">
    						<?php echo lang('button_active'); ?>
    					</a>
    					<?php endif;?>
    					
    					 <?php if($topup_offline->status != mod('order')->status('canceled')):?>
    					<a href="" _url="<?php echo $topup_offline->_url_cancel; ?>"
    					   title="<?php echo lang('notice_are_you_sure_want_to_cancel'); ?>"
    					   class="btn btn-primary btn-xs  verify_action"
    					   notice="<?php echo lang('notice_are_you_sure_want_to_cancel'); ?>:<br><strong><?php echo  $topup_offline->id; ?></strong>"
    						>
    						<?php echo lang('button_cancel'); ?>
    					</a>
                        <?php endif;?>
                        <div class="clear"></div>
    				</li>
    				
    				
                            
        		</ul>
        	</div>
        </div>
     </div>
</div>
