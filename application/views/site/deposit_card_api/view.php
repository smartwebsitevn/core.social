<div class="panel panel-default">
     <div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_deposit_card_api')?></h3>
	 </div>
	 <div class="panel-body">
	       <p><b><?php echo lang('deposit_card_api_id')?> :</b> <span style="color:red"><?php echo site_url('nap/{id thành viên}')?></span> </p>
	       <p><b><?php echo lang('deposit_card_api_phone')?> :</b> <span style="color:red"><?php echo site_url('nap/{Số điện thoại thành viên}')?></span> </p>
	       <p><b><?php echo lang('deposit_card_api_username')?> :</b> <span style="color:red"><?php echo site_url('nap/{Tài khoản thành viên}')?></span> </p>
	       
	       <?php $phone = isset($user->phone) ? $user->phone : '';?>
	       <?php if($phone):?>
	       <a href="<?php echo site_url('nap/'.$phone)?>" target="_blank" class="btn btn-default"><?php echo lang('deposit_card_api_id')?></a>	
		   Hoặc
		   <a href="<?php echo site_url('nap/'.$phone)?>" target="_blank" class="btn btn-default"><?php echo lang('deposit_card_api_phone')?></a>	
		   Hoặc 
		   <a href="<?php echo site_url('nap/'.$user->username)?>" target="_blank" class="btn btn-default"><?php echo lang('deposit_card_api_username')?></a>	
		   <?php endif;?>	
	 </div>
</div>