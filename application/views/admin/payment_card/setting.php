<?php
/*
	$args = $this->data;

	$args['form'] = [

		'title' => lang('payment_card_'.$code),
	];
	foreach ($params as $p):
    	$args['form']['rows'][] = array(
    	    'param' => $p,
    	    'name'  => lang("payment_card_{$code}_{$p}"),
    	    'type'  => 'text',
    	    'value' => $setting[$p],
    	    'req' 	=> true,
    	);
	endforeach;
	echo macro()->page($args);
*/
$_id = '_' . random_string('unique');
?>
<?php $this->load->view('admin/payment_card/_js');?>
<div class="row">
			<div class="col-lg-12">
				<!-- BEGIN BREADCRUMB -->
				<div class="breadcrumbs fixed">
					<!-- Breadcrumbs -->
					<ul class="breadcrumb">
							<li>
								<a href="<?php echo admin_url('admin')?>">Home</a>
							</li>
							<li><a href="<?php echo admin_url('payment_card')?>">Cổng gạch thẻ</a></li>
                            <li class="active">Cài đặt</li>
                      </ul>										
				</div>
				<!-- END BREADCRUMB -->

				<!-- PAGE TITLE ROW -->
                <div class="page-header title">
                		<h1>Cổng gạch thẻ <span class="sub-title">Danh sách</span></h1>								
                </div>
			</div><!-- /.col-lg-12 -->
</div>
		
<div class="row">
		<div class="col-lg-12">
    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-pencil"></i><?php echo lang('payment_card_'.$code)?></h4>
            </div>
            <div class="portlet-widgets">
                <span class="divider"></span>
                <a href="#<?php echo $_id?>" data-toggle="collapse" data-parent="#accordion"><i class="fa fa-chevron-down"></i></a>
            </div>
        </div>
        <?php $types = $this->payment_card->{$code}->get_types();?>
                       
        <div id="<?php echo $_id?>" class="panel-collapse collapse in">
            <div class="portlet-body ">
                <form action="<?php echo current_url()?>" id="form" class="form form-horizontal " accept-charset="UTF-8" method="post">
                       
                        <div id="content_add_account"> 
                                <?php foreach ($setting as $k => $account):?>
                                 <div> 
                                    <?php $key = ($k == 0) ? $k+1 : $k;?>
                                    <div id="account_<?php echo $key?>">
                                    <div class="form-group param_text  ">
                                             <label  class="col-sm-3  control-label">
                                             <b class="req"><?php echo lang('account')?> <?php echo $key?> </b>
                                             </label>
                                             <div class="col-sm-9">
                                                  <a href='javascript:void(0)' class="btn btn-danger" onclick='del_account(<?php echo $key?>)'  title='<?php echo lang('del_account')?>'>
                                                      <?php echo lang('del_account')?> 
                                                   </a>
                                             </div>
                                    </div>
                                    <?php foreach ($account as $p => $val):?>
                                    <div class="form-group param_text  ">
                                         <label for="_<?php echo $p.'_'.$_id?>" class="col-sm-3  control-label "><?php echo lang("payment_card_{$code}_{$p}")?>:
                                            <span class="req">*</span>
                                        </label>
                                        <div class="col-sm-9">
                                              <?php if($p == 'default'):?>
                                               <label class="tcb-inline ">
                                                    <input type="radio" value="<?php echo $key?>" <?php echo form_set_checkbox($key, $val)?> name="<?php echo $p?>" class="tc">                                    
                                                    <span class="labels"> Sử dụng</span>
                                                </label>
                                              <?php else:?>
                                              <input type="text" value="<?php echo $val?>" name="<?php echo $p?>[<?php echo $key?>]" class="form-control ">
                                              <?php endif;?> 
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <?php endforeach;?>    
                                     </div>
                                     
                                      <div class="form-group param_text">
                                             <label for="_<?php echo 'discount_'.$_id?>" class="col-sm-3  control-label ">Chiết khấu (%):
                                                <span class="req">*</span>
                                            </label>
                                            <div class="col-sm-9">
                                                   <?php foreach ($types as $type):?>
                                                        <div style="float:left;width:140px;">
                                                             <?php echo $type?> <input style='width:50px;display:inline-block' type="text" value="<?php echo isset($discounts[$key][$type]) ? $discounts[$key][$type] : 0;?>" name="discount_<?php echo $type?>_<?php echo $key?>" class="form-control ">
                                                        </div> 
                                                   <?php endforeach;?>
                                                    <div class="clearfix"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                       
                                  </div>
                                <?php endforeach;?>
                                <?php foreach ($params as $p):?>
                                 <div class="error help-block" name="<?php echo $p?>_error"></div>
                                <?php endforeach;?>
                        </div>
                        
                        <div class="form-group param_text  ">
                                 <label  class="col-sm-3  control-label ">
                                 </label>
                                 <div class="col-sm-9">
                                       <a  class="btn btn-primary" href="#" id="add_account" _part="<?php echo (count($setting) >= 1) ? count($setting) : 0?>">
                							<i class="fa fa-plus"></i> 
                							<span><?php echo $this->lang->line('add_account'); ?></span>
                						</a>
                                 </div>
                        </div>
                        
                       
                        <!-- account temp -->
                        <div id="poll_account_temp" class="hide">
                               <div  id="account_{account}">
                                     <div class="form-group param_text">
                                         <label  class="col-sm-3  control-label">
                                          <b class="req"><?php echo lang('account')?> {account}</b>
                                         </label>
                                         <div class="col-sm-9">
                                              <a href='javascript:void(0)' class="btn btn-danger" onclick='del_account({account})'  title='<?php echo lang('del_account')?>'>
                                                  <?php echo lang('del_account')?>
                                               </a>
                                         </div>
                                    </div>
                            		 <?php foreach ($params as $p):?>
                                    <div class="form-group param_text">
                                          <label for="_<?php echo $_id?>" class="col-sm-3  control-label "><?php echo lang("payment_card_{$code}_{$p}")?>:
                                            <span class="req">*</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <?php if($p == 'default'):?>
                                           <label class="tcb-inline ">
                                                <input type="radio" value="{account}" {param_name}="<?php echo $p?>" class="tc">                                    
                                                <span class="labels"> Sử dụng</span>
                                            </label>
                                          <?php else:?>
                                              <input type="text" value="" {param_name}="<?php echo $p?>[{account}]" id="_<?php echo $_id?>" class="form-control ">
                                           <?php endif;?>  
                                           <div class="error help-block" name="<?php echo $p?>_error"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <?php endforeach;?>
                                    
                                    <div class="form-group param_text">
                                         <label for="_<?php echo 'discount_'.$_id?>" class="col-sm-3  control-label ">Chiết khấu (%):
                                            <span class="req">*</span>
                                        </label>
                                        <div class="col-sm-9">
                                               <?php foreach ($types as $type):?>
                                                    <div style="float:left;width:140px;">
                                                         <?php echo $type?> <input style='width:50px;display:inline-block' type="text" value="" name="discount_<?php echo $type?>_{account}" class="form-control ">
                                                    </div> 
                                               <?php endforeach;?>
                                                <div class="clearfix"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                        
                                </div>
                        </div>
                        <div class="clear"></div>

                   
                        <div class="clear"></div>
                        
                        <div class="form-actions">
                            <div class="form-group formSubmit">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Thực hiện">
                                    <a class="btn" href="<?php echo admin_url('payment_card')?>">Hủy bỏ</a>
                                </div>
                            </div>
                        </div>
                        
                </form>            
            </div>
        </div>
    </div> 		
</div>
</div>
