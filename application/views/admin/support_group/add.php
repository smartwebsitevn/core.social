<script type="text/javascript"> 
(function($)
{
	$(document).ready(function()
	{
		var main = $('#main_popup');

		// Form action
		main.nstUI({
			method:	'formAction',
			formAction:	{
				field_load: main.attr('_field_load')
			}
		});
		
		// Close colorbox
		main.find('input[type=reset]').click(function()
		{
			$.colorbox.close();
			return false;
		});
		
	});
})(jQuery);
</script>


<style>
#main_popup .formRow {
	padding: 16px 14px;
}
#main_popup .formRow .formRight {
	width: 70%;
}
#main_popup .formRow .autocheck {
	margin-left: 5px;
}
</style>


<form class="form" id="main_popup" action="<?php echo $action; ?>" method="post">
	<div class="widget mg0" style="width:500px; height:350px;">
		<div class="title">
			<img src="<?php echo public_url('admin'); ?>/images/icons/dark/add.png" class="titleIcon" />
			<h6><?php echo lang('add'); ?> <?php echo lang('mod_cat'); ?></h6>
		</div>
		
		<div class="formRow">
			<label class="formLeft" for="param_name"><?php echo lang('name'); ?>:<span class="req">*</span></label>
			<div class="formRight">
				<input name="name" id="param_name" _autocheck="true" class="left" style="width:250px;" type="text" />
				<span name="name_autocheck" class="autocheck"></span>
				<div name="name_error" class="clear error"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		
		<div class="formRow">
			<label class="formLeft" for="param_sort_order"><?php echo lang('sort_order'); ?>:</label>
			<div class="formRight">
				<input name="sort_order" id="param_sort_order" _autocheck="true" class="left" style="width:100px;" type="text" />
				<span name="sort_order_autocheck" class="autocheck"></span>
				<div name="sort_order_error" class="clear error"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" value="<?php echo lang('button_add'); ?>" class="redB" />
			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
		</div>
		<div class="clear"></div>
		
	</div>
</form>
