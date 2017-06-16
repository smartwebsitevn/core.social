
	
        <form id="main_popup" action="<?php echo current_url() ?>" method="post">
			
			<div class="main_form_info">
				<div class="form-row">
					<label class="form-label" for="param_filename"><?php echo lang('name'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<span class="one"><input name="filename" value="<?php echo isset($info->name) ? $info->name : ''?>" id="param_filename" type="text" class="t-input" /></span>
						<div name="filename_autocheck" class="autocheck"></div>
						<div class="clear"></div>
						<div name="filename_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>


			</div>
			
			<div class="main_form_notice">
				<p>- <?php echo lang('title_max_files_size')?>: <?php echo $config['maxfilesize']?> MB.</p>
				<p>- <?php echo lang('title_file_format')?>: <?php echo str_replace('|', ', ', $config['allowed_types'])?>.</p>
				<p>- <?php echo lang('title_file_min_description',40)?>.</p>
				<p>- <?php echo lang('title_file_description_required_english')?>.</p>
			</div>
			
			<div class="clearfix"></div>
				
				<div class="form-row">
					<label class="form-label" for="param_name"><?php echo lang('description'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<span class="one"><textarea  class="t-input editor" id="description" name="description"><?php echo isset($info->description) ? $info->description : ''; ?></textarea></span>
						<div name="description_autocheck" class="autocheck"></div>
						<div class="clear"></div>
						<div name="description_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear" style="height:10px;"></div>
				<div class="form-row " id="button_update_file">
					<label class="form-label">&nbsp;</label>
					<div class="form-item">
			           	<input type="submit" value="<?php echo lang('button_update'); ?>" class="button button-border medium blue f" />
					</div>
				</div>
		</form>
		<style>
		.form-item textarea {
    height: 130px;
}
input, button, select, textarea {
    max-width: 100%;
}
		</style>