
<!-- Common -->
<?php $this->load->view('admin/site/_common'); ?>

<!-- Main content wrapper -->
<div class="wrapper">

   	<!-- Form -->
	<form action="<?php echo $action; ?>" class="form" id="form" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/settings.png" class="titleIcon" />
					<h6><?php echo lang('mod_site_setting'); ?> <?php echo lang('mod_site'); ?></h6>
				</div>
				
				<div class="formRow">
					<label class="formLeft"><?php echo lang('notice_status'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<input type="radio" name="notice" value="0" id="notice_0" <?php echo form_set_checkbox('0', $setting['notice']); ?> />
						<label for="notice_0"><?php echo lang('no');?></label>
						
						<input type="radio" name="notice" value="1" id="notice_1" <?php echo form_set_checkbox('1', $setting['notice']); ?> />
						<label for="notice_1"><?php echo lang('yes');?></label>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft"><?php echo lang('notice_content'); ?>:</label>
					<div class="formRight">
						<textarea name="notice_content" id="param_notice_content" class="editor" 
							_config='{
								"height": 200
							}'
						><?php echo $setting['notice_content']; ?></textarea>
						<div name="notice_content_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
           		<div class="formSubmit">
           			<input type="submit" value="<?php echo lang('button_update'); ?>" class="redB" />
           			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
           		</div>
        		<div class="clear"></div>
        		
			</div>
		</fieldset>
	</form>
	
</div>
