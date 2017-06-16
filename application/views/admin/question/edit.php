
<?php $this->load->view('admin/question/_common'); ?>

<!-- Main content wrapper -->
<div class="wrapper">
	
   <!-- Form -->
	<form class="form" id="form">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/add.png" class="titleIcon" />
					<h6><?php echo lang('edit'); ?> <?php echo lang('mod_question'); ?></h6>
				</div>
				
				<div class="formRow">
					<label class="formLeft" for="param_title"><?php echo lang('title'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span class="oneTwo"><input name="title" value="<?php echo $question->title; ?>" id="param_title" _autocheck="true" type="text" /></span>
						<span name="title_autocheck" class="autocheck"></span>
						<div name="title_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				
				
				<div class="formRow">
					<label class="formLeft" for="param_content"><?php echo lang('content'); ?>:<span class="req">*</span></label>
					<div class="clear"></div>
					<div class="formRight">
						<div class="editor_border"><textarea name="content" id="param_content" class="editor" style="height:200px;"  ><?php echo $question->content; ?></textarea></div>
						<div name="content_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label for="sort_order"><?php echo $this->lang->line('sort_order'); ?>:</label>
					<div class="formRight">
						<span class="oneFour"><input name="sort_order" id="sort_order" class="sort_order" _autocheck="true" value="<?php echo $question->sort_order; ?>" type="text" /></span>
						<span name="sort_order_autocheck" class="autocheck"></span>
						<div name="sort_order_error" class="clear error"></div>
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
   	<!-- Upload images -->
	<?php $this->widget->admin->upload_file($widget_upload_images); ?>
	
</div>
