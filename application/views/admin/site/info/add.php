<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('#form');

		// Hien thi form nhap gia tri cua info theo type
		show_info_value_type();
		
		main.find('[name=type]').change(function()
		{
			show_info_value_type();
		});
		
		function show_info_value_type()
		{
			var val = main.find('[name=type]:checked').val();
			
			main.find('[_type]').hide();
			main.find('[_type='+val+']').fadeIn();
		}
		
	});
})(jQuery);
</script>


<style>
[_type] {
	display: none;
}
</style>


<!-- Common -->
<?php $this->load->view('admin/site/_common'); ?>

<!-- Main content wrapper -->
<div class="wrapper">

   	<!-- Form -->
	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/add.png" class="titleIcon" />
					<h6><?php echo lang('add'); ?> <?php echo lang('mod_site_info'); ?> <?php echo lang('mod_site'); ?></h6>
				</div>
				
				<div class="formRow">
					<label for="param_key"><?php echo lang('key'); ?>:<span class="req">*</span></label>
					<div class="formRight">
						<span class="oneTwo"><input name="key" id="param_key" _autocheck="true" type="text" /></span>
						<span name="key_autocheck" class="autocheck"></span>
						<div name="key_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="formRow">
					<label class="formLeft"><?php echo lang('value'); ?>:<span class="req">*</span></label>
					
					<div class="formRight">
						<?php foreach ($types as $i => $v): ?>
							<input type="radio" name="type" value="<?php echo $i; ?>" id="param_type_<?php echo $i; ?>" <?php echo form_set_checkbox($i, '0'); ?> />
							<label for="param_type_<?php echo $i; ?>"><?php echo ucfirst($v); ?></label>
						<?php endforeach; ?>
						<div class="clear pb5"></div>
					
						<?php $i = array_search('text', $types); ?>
						<div _type="<?php echo $i; ?>">
							<input name="value[<?php echo $i; ?>]" id="param_value_<?php echo $i; ?>" type="text" />
						</div>
						
						<?php $i = array_search('list', $types); ?>
						<div _type="<?php echo $i; ?>">
							<textarea name="value[<?php echo $i; ?>]" id="param_value_<?php echo $i; ?>" class="autoGrow" rows="5" cols=""></textarea>
							<div class="clear note f11"><?php echo lang('note_value_list'); ?></div>
						</div>
						
						<?php $i = array_search('html', $types); ?>
						<div _type="<?php echo $i; ?>">
							<textarea name="value[<?php echo $i; ?>]" id="param_value_<?php echo $i; ?>" class="editor" 
								_config='{
									"height": 200
								}'
							></textarea>
						</div>
						
						<div name="value_error" class="clear error"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
           		<div class="formSubmit">
           			<input type="submit" value="<?php echo lang('button_add'); ?>" class="redB" />
           			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
           		</div>
        		<div class="clear"></div>
        		
			</div>
		</fieldset>
	</form>
	
</div>
