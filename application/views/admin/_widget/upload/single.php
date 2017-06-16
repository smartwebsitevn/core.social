
<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('.upload_<?php echo $_rd; ?>').pluploadScript({
			mod: 				'single',
			plugin_path:		'<?php echo public_url('js'); ?>/jquery/plupload',
			url_upload:			'<?php echo $upload_url['upload']; ?>',
			url_get:			'<?php echo $upload_url['get']; ?>',
			url_update:			'<?php echo $upload_url['update']; ?>',
			config_extensions:	'<?php echo str_replace('|', ',', $upload_config['allowed_types']); ?>',
			config_max_size:	'<?php echo $upload_config['max_size']/1024; ?>'
		});
	});
})(jQuery);
</script>


<div class="upload_<?php echo $_rd; ?> left">
	<div class="upload_complete link1"></div>
	<div class="clear"></div>
	
	<div class="upload_info link1" style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_error" style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_action link1">
		<a href="" id="action_upload"><?php echo $this->lang->line('browse_file'); ?></a> | 
		<a href="" id="action_del"><?php echo $this->lang->line('del_file'); ?></a>
		
		<div class="f11 formNote">
			<?php echo $this->lang->line('max_size'); ?>: <b><?php echo $upload_config['max_size']/1024; ?>Mb</b> - 
			<?php echo $this->lang->line('allowed_types'); ?>: <b><?php echo str_replace('|', ', ', $upload_config['allowed_types']); ?></b>
		</div>
	</div>
	<div class="clear"></div>
	
	
	<!-- Temp html -->
	<div id="temp" style="display:none">
		<div id="upload_complete">
			<a href="{file_url}" target="_blank"><h6 class="blue" style="display:inline;">{file_name}</h6></a>
		</div>
		
		<div id="upload_info">
			<div><b>{file_name}</b> ({file_size})</div>
			<div class="contentProgress" style="width:150px; margin-top:3px;"><div class="progress barO" style="width:{file_progress}%;"></div></div>
		</div>
		
		<div id="upload_error">
			<div><b>{file_name}</b> ({file_size})</div>
			<div class="error f12">Error: {file_error}</div>
		</div>
		
	</div>
	
</div>