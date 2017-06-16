
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('#main_popup');
		
		// Tabs
		$main.contentTabs();

		// editor
		call_editor($main);
		
		// Form action
		$main.find('.form').nstUI('formAction', {
			event_complete: function(data)
			{
				$.jGrowl('<?php echo lang('notice_update_success'); ?>');
			}
		});
		
	});
})(jQuery);
</script>


<style>
#main_popup form .param_textarea textarea {
	height: 100px;
}
#main_popup form .param_html .formRight {
	width: 100%;
	margin: 0px;
}
</style>


<div id="main_popup" style="width:800px;">
<form action="<?php echo $action; ?>" class="form" id="form" method="post">
<fieldset>
	<div class="widget">
		<ul class="tabs">
			<?php foreach ($langs as $lang): ?>
				<li><a href="#lang_<?php echo $lang->id; ?>">
					<img class="lang_img" src="<?php echo public_url("img/lang/{$lang->directory}.png"); ?>">
					<span class="lang_name"><?php echo $lang->name; ?></span>
				</a></li>
			<?php endforeach; ?>
		</ul>
		
	   <div class="tab_container">
			<?php foreach ($langs as $lang): ?>
				<div id="lang_<?php echo $lang->id; ?>" class="tab_content pd0">
				
					<?php
						foreach ($field as $p => $o)
						{
							$_data = array();
							$_data['opt']	= $o;
							$_data['name'] 	= "{$p}[{$lang->id}]";
							$_data['value'] = (isset($info[$p][$lang->id])) ? $info[$p][$lang->id] : $o['value'];
							$this->load->view('admin/_common/param_form', $_data);
						}
					?>
					
					<div class="formRow hide"></div>
				</div>
			<?php endforeach; ?>
		</div>	
		<div class="clear"></div>
		
		<div class="formSubmit">
			<input type="submit" value="<?php echo lang('button_update'); ?>" class="redB" />
			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
		</div>
		<div class="clear"></div>
		
	</div>
</fieldset>
</form>
</div>