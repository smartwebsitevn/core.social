
<?php echo macro()->page(['toolbar' => []]); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/edit.png" class="titleIcon" />
					<h6><?php echo lang('edit'); ?> <?php echo lang('mod_email'); ?></h6>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_key"><?php echo lang('key'); ?>:</label>
					<div class="formRight fontB mt5">
						<?php echo $info->key; ?>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_title"><?php echo lang('title'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span class="oneTwo"><input name="title" value="<?php echo $info->title; ?>" id="param_title" _autocheck="true" type="text" /></span>
						<span name="title_autocheck" class="autocheck"></span>
						<div name="title_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_content"><?php echo lang('content'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<textarea name="content" id="param_content" class="editor"><?php echo $info->content; ?></textarea>
						<div name="content_error" class="clear error"></div>
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
