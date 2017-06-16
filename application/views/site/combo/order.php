<style>
.service_info li span{
	display:inline-block;
	width:150px;
}
.form-horizontal .col-sm-9 label {
    cursor: pointer;
    font-weight: normal;
}
</style>
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('form#form_order');
		
        var customer_content = $('#customer_content');
        
		main.find('input[name=email]').change(function(){
			var email = $(this).val();
			if(email)
			{
				$(this).nstUI(
		  				{
		    		    method:	"loadAjax",
			  			loadAjax:{
			  				url: '<?php echo site_url('service_order/load_customer')?>',
			  				data: {'email': email},
			  				field: {load: 'customer_content_load', show: ''},
			  				event_complete: function(data)
			  				{
			  					customer_content.slideDown(function(){ $(this).show(); });
			  					customer_content.html(data);
			  				}
			  			},
			  	 });  
			}else{
				customer_content.html('');
			}
			
		});
	});
})(jQuery);
</script>

	<?php
	$mr = [];
	
	$mr['content'] = function() use ($combo, $pservices)
	{
			ob_start();?>
	       <form class="form-horizontal form_action" id="form_order" action="<?php echo current_url()?>" accept-charset="UTF-8" method="post">
    		    
    		    <?php 
            		echo macro('mr::form')->row_title(lang('combo_info'));
            		
            		echo macro('mr::form')->row(
            		    array(
            		        'param' => 'name',
            		        'type' 	=> lang('custom'),
            		        'html'  => '<b class="fontB f16 fontB">'.$combo->name.'</b>',
            		    )
            		);	
        		?>
        		
    		     <?php 
        		if(user_is_login()):
            		echo macro('mr::form')->row(
            		    array(
            		        'param' => 'email',
            		        'req'   => 1,
            		    )
            		);
        		endif;
        		?>
        		<div style="position: relative;">
                    <div id="customer_content_load" class="form_load"></div>
                    <div id="customer_content">    </div>
                </div>
            
    		     <!-- Bảng giá -->
                <?php if($combo->payment_type == 'onetime'){?>
                      <div class="form-group param_custom">
                		<label class="col-sm-3 control-label">
                			<?php echo lang('price')?>:			
                		</label>
                		<div class="col-sm-9">
                    		 <b class="red"><?php echo $combo->_price?></b>
                			<div class="clearfix"></div>		
                		</div>
                	  </div>	
                	  <div class="form-group param_custom">
                		<label class="col-sm-3 control-label">
                			<?php echo lang('price_setup')?>:			
                		</label>
                		<div class="col-sm-9">
                    		 <b class="red"><?php echo $combo->_price_setup?></b>
                			<div class="clearfix"></div>		
                		</div>
                	  </div>
                <?php }elseif($combo->payment_type == 'free'){?>
                	  <div class="form-group param_custom">
                		<label class="col-sm-3 control-label">
                			<?php echo lang('price')?>:			
                		</label>
                		<div class="col-sm-9">
                    		 <b class="red"><?php echo lang('fee_price')?></b>
                			<div class="clearfix"></div>		
                		</div>
                	  </div>
                 <?php }elseif($combo->payment_type == 'recurring'){?>
                	  <div class="form-group param_custom">
                		<label class="col-sm-3 control-label">
                			<?php echo lang('price_time')?> :<span class="req">*</span>		
                		</label>
                		<div class="col-sm-9">
                    		<?php foreach ($combo->_price_times as $time => $row):?>
                    		    <?php if($row[0] == config('invalid_amount', 'pservice')) continue;?>
                    		    <label class="fontB f13">
    							     <input type="radio"  value="<?php echo $time?>" name="price_time"></span>
    							     <?php echo $time. ' '. lang('month')?>  x <?php echo currency_format_amount($row[0])?> = <?php echo currency_format_amount($row[1])?>                                                        						
    							</label>
    							<div class="clearfix"></div>
                    		<?php endforeach;?>
                			<div class="clearfix"></div>
                			<div class="form-error" name="price_time_error"></div>		
                		</div>
                	  </div>
                <?php }?> 
                
                <?php echo macro('mr::form')->row_title(lang('service_info'));?>
                
                <?php foreach ($pservices as $row):?>
                      <div class="form-group param_custom">
                		<label class="col-sm-3 control-label">
                			<?php echo $row->name?>:			
                		</label>
                		<div class="col-sm-9">
                		     <?php 
                		     foreach ($row->product_type_requireds as $req)
                		     {
                		         echo macro('mr::form')->row(
                		             array(
                		                 'param' => 'input_required_'.$req.'_'.$row->id,
                		                 'name'  => lang('input_required_'.$req),
                		                 'req'  => true
                		             )
                		         );
                		     }
                		     ?>  
                		     <?php if(!empty($row->options)):?>
                		     <div class="form-group param_text">
                		          <label class="col-sm-3 control-label" for="_5be8697a7f8b07e3b4286545ddc31d71">
                		             <?php echo lang('desc')?>
                		          </label>
                		          <div class="col-sm-9">
                		               <ul>
                		                     <?php foreach ($row->options as $key => $option):?>
                                                <?php if($key > 3) break;?>
                                                <?php if(strtolower($option->value) == 'yes'):?>
                                                     <li class="list_option"><span><?php echo $option->name?></span></li>
                                                <?php else:?>
                                                    <li class="list_option"><span><b><?php echo $option->name?>:</b> <?php echo $option->value?></span></li>
                                                <?php endif;?>
                                                <?php endforeach;?> 
                                           
                                         </ul>
                                        
                		          </div>
                		     </div>
                    		 <?php endif;?>
                			<div class="clearfix"></div>		
                		</div>
                	  </div>
                <?php endforeach;?>
                
    	        <div class="form-group">
            		<div class="col-sm-offset-3 col-sm-9">
            			<input type="submit" value="<?php echo lang('buy_combo')?>" class="btn btn-default"></div>
            	</div>
    		</form>
    		
    		<div class="clear"></div>
		
			<p class="text-justify">
				<?php echo html_entity_decode($combo->description); ?>
			</p>
	
			<?php return ob_get_clean();
		};
	
		echo macro('mr::box')->box([
			'title' => $combo->name,
			'body'  => $mr['content'](),
		]);