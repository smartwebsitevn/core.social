<?php
	$mr = [];

	$mr['order_service'] = function() use ($invoice_order, $service_order)
	{
			ob_start();?>
			<?php 
			$body = macro()->info([
			    lang('id')               => $invoice_order->id,
			    lang('total')            => currency_format_amount($invoice_order->amount),
			    lang('created')          => get_date($invoice_order->created, 'full'),
			]);
			
			echo macro('mr::box')->box([
			    'title'   => lang('title_invoice_order_view'),
			    'content' => $body,
			]);
			?>
			<div class="portlet">
			<div class="portlet-heading bg-primary">
				<div class="portlet-title">
					<h4><?php echo lang('title_service_order_info')?></h4>
				</div>
				<div class="clearfix"></div>
			</div>
			<table class="table table-bordered table-hover">
	
				<thead>
				   <tr>
	        			<th><?php echo lang('type'); ?></th>
	        			<th><?php echo lang('desc'); ?></th>
	        			<th><?php echo lang('expire'); ?></th>
	        			<th><?php echo lang('status'); ?></th>
	    		   </tr>
				</thead>
	
				<tbody>
				    
				         <tr>
	    			         <td></td>
	    			         <td>

	    			         </td>
	    			         
	    			         <td>
	    			         <p>
	    			         <?php echo ($service_order->expire_to) ? get_date($service_order->expire_from).' - '.get_date($service_order->expire_to) : ''?>
	    			         </p>

	    			         </td>
	    			        
	    			         <td>
	    			         <?php echo macro()->status_color($service_order->status, lang('service_status_' . $service_order->status))?>
	    			         <?php echo '<a class="btn btn-primary btn-xs lightbox" title="'.lang('edit').'" href="'.admin_url('service_order/edit/'.$service_order->id).'">'.lang('edit').'</a>';
        	    			        /* if(mod('service_order')->can_do($service_order, 'renew'))
        	    			         {
        	    			             echo ' <a href="'. admin_url('service_order/renew/'.$service_order->id) .'" class="btn btn-primary btn-xs lightbox" title="'.lang('renew').'" >'.lang('renew').'</a>';
        	    			         }*/
	    			               echo '<p style="margin-top:10px"><b>'.lang('last_update_status').':</b> '.get_date($service_order->last_update_status, 'full').(($service_order->admin_update) ? "<b style='color:red'> - ".$service_order->admin_update."</b>" : "").'</p>';
	    			         ?>
	    			         
	    			         </td>
				         </tr>
				</tbody>
	
			</table>
	
			
	        </div>
			<?php return ob_get_clean();
		};
		
?>

<?php 

echo macro()->page([
    'toolbar'  => [],
    'contents' => $mr['order_service'](),
]);
?>
