<div class="t-box" id="main_content">
	<div class="box-title">
		<h1><?php echo lang('title_user_verify'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<form class="t-form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

			<!-- Thong tin -->
			<div class="left f14 blue fontB"><?php echo lang('user_info'); ?>:</div>
					
			<div class="form-row">
				<label class="form-label" for="param_name"><?php echo lang('full_name'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="name" value="<?php echo form_set_input(set_value('name'), $user_verify->name); ?>" id="param_name" _autocheck="true" type="text" class="t-input" /></span>
					<div name="name_autocheck" class="autocheck"></div>
					<div class="clear"></div>
					<div name="name_error" class="error"><?php echo form_error('name'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
				
			<div class="form-row">
				<label class="form-label" for="param_phone"><?php echo lang('phone'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="phone" value="<?php echo form_set_input(set_value('phone'), $user_verify->phone); ?>" id="param_phone" _autocheck="true" type="text" class="t-input" /></span>
					<span name="phone_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div name="phone_error" class="error"><?php echo form_error('phone'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label" for="param_address"><?php echo lang('address'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="address" value="<?php echo form_set_input(set_value('address'), $user_verify->address); ?>" id="param_address" _autocheck="true" type="text" class="t-input" /></span>
					<span name="address_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div name="address_error" class="error"><?php echo form_error('address'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label" for="param_card_no"><?php echo lang('card_no'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="card_no" value="<?php echo form_set_input(set_value('card_no'), $user_verify->card_no); ?>" id="param_card_no" _autocheck="true" type="text" class="t-input" /></span>
					<span name="card_no_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div name="card_no_error" class="error"><?php echo form_error('card_no'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label" for="param_card_place"><?php echo lang('card_place'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="card_place" value="<?php echo form_set_input(set_value('card_place'), $user_verify->card_place); ?>" id="param_card_place" _autocheck="true" type="text" class="t-input" /></span>
					<span name="card_place_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div name="card_place_error" class="error"><?php echo form_error('card_place'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label" for="param_card_date"><?php echo lang('card_date'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><input name="card_date" value="<?php echo form_set_input(set_value('card_date'), $user_verify->card_date); ?>" id="param_card_date" _autocheck="true" type="text" class="t-input datepicker" /></span>
					<span name="card_date_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div name="card_date_error" class="error"><?php echo form_error('card_date'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<?php /*?>
			<div class="form-row">
				<label class="form-label" for="param_paypal_emails"><?php echo lang('paypal_emails'); ?>:<span class="req">*</span></label>
				<div class="form-item">
					<span class="one"><textarea name="paypal_emails" id="param_paypal_emails" _autocheck="true" class="t-input" rows="4" cols=""><?php echo form_set_input(set_value('paypal_emails'), implode("\n", $user_verify->paypal_emails)); ?></textarea></span>
					<span name="paypal_emails_autocheck" class="autocheck"></span>
					<div class="clear"></div>
					<div class="note"><?php echo lang('note_paypal_emails'); ?></div>
					<div class="clear"></div>
					<div name="paypal_emails_error" class="error"><?php echo form_error('paypal_emails'); ?></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php */?>
			
			<!-- Hinh anh -->
			<div class="left f14 blue fontB"><?php echo lang('user_images'); ?>:</div>
			
			<?php foreach ($image_params as $p): ?>
				<div class="form-row">
					<label class="form-label" for="param_image_<?php echo $p; ?>"><?php echo lang('image_'.$p); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<?php if ($user_verify->{'_can_upload_'.$p}): ?>
							<span class="one"><input name="image_<?php echo $p; ?>" id="param_image_<?php echo $p; ?>" type="file" /></span>
							<div class="clear"></div>
							<div name="image_<?php echo $p; ?>_error" class="error"><?php echo form_error('image_'.$p); ?></div>
						<?php else: ?>
							<b class="blue pt5 left"><?php echo lang('upload_completed'); ?></b>
						<?php endif; ?>
					</div>
					<div class="clear"></div>
				</div>
			<?php endforeach; ?>
			
			<div class="form-row">
				<label class="form-label">&nbsp;</label>
				<div class="form-item f11">
					<?php echo lang('max_size'); ?>: <b><?php echo $upload_config['max_size']/1024; ?>Mb</b> - 
					<?php echo lang('allowed_types'); ?>: <b><?php echo str_replace('|', ', ', $upload_config['allowed_types']); ?></b>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="form-row">
				<label class="form-label">&nbsp;</label>
				<div class="form-item">
					<input type="hidden" name="_submit" value="1" />
					<input type="submit" value="<?php echo lang('button_verify'); ?>" class="button button-border medium blue f" />
				</div>
			</div>
			
		</form>
		<div class="clear"></div>
	</div>
</div>