
<?php $this->load->view('admin/seo_word/_common'); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/edit.png" class="titleIcon" />
					<h6><?php echo lang('add'); ?> <?php echo lang('mod_seo_word'); ?></h6>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_url"><?php echo lang('url'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span class="threeOne"><input name="url" value="<?php echo htmlentities($info->url); ?>" id="param_url" type="text" /></span>
						<span name="url_autocheck" class="autocheck"></span>
						<div name="url_error" class="clear error"></div>
						<div class="formNote clear"><?php echo lang('note_param_sub_url'); ?></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_title"><?php echo lang('title'); ?>:</label>
					<div class="formRight">
						<span class="threeOne"><input name="title" value="<?php echo $info->title; ?>" id="param_title" type="text" /></span>
						<span name="title_autocheck" class="autocheck"></span>
						<div name="title_error" class="clear error"></div>
						<div class="formNote clear"><?php echo lang('note_param_old_value', $param_old_value); ?></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_description"><?php echo lang('description'); ?>:</label>
					<div class="formRight">
						<span class="threeOne"><textarea name="description" id="param_description" rows="4" cols=""><?php echo $info->description; ?></textarea></span>
						<span name="description_autocheck" class="autocheck"></span>
						<div name="description_error" class="clear error"></div>
						<div class="formNote clear"><?php echo lang('note_param_old_value', $param_old_value); ?></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_keywords"><?php echo lang('keywords'); ?>:</label>
					<div class="formRight">
						<span class="threeOne"><textarea name="keywords" id="param_keywords" rows="4" cols=""><?php echo $info->keywords; ?></textarea></span>
						<span name="keywords_autocheck" class="autocheck"></span>
						<div name="keywords_error" class="clear error"></div>
						<div class="formNote clear"><?php echo lang('note_param_old_value', $param_old_value); ?></div>
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
