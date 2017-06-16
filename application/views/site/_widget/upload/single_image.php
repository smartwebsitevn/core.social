
<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('.upload_<?php echo $_rd; ?>').pluploadScript({
			mod: 				'single_image',
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
	<div class="upload_complete"></div>
	<div class="clear"></div>
	
	<div class="upload_info link1" style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_error" style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_action link1" style="margin-top:2px;">
		<a href="" id="action_upload"><?php echo $this->lang->line('browse_file'); ?></a>
		<span id="span_action_del" style="display:none"> | <a href="" id="action_del" ><?php echo $this->lang->line('del_file'); ?></a></span>
		<div class="f11 formNote">
			<?php echo $this->lang->line('max_size'); ?>: <b><?php echo $upload_config['max_size']/1024; ?>Mb</b> - 
			<?php echo $this->lang->line('allowed_types'); ?>: <b><?php echo str_replace('|', ', ', $upload_config['allowed_types']); ?></b>
		</div>
	</div>
	<div class="clear"></div>
	
	
	<!-- Temp html -->
	<div id="temp" style="display:none">
		<div id="upload_complete">
			<div class="file_image_single">
				<div class="file_image_single_img">
					<a href="{file_url}" onclick="lightbox(this); return false;"><img style="max-height: 120px"></a>
				</div>
			</div>
		</div>
		
		<div id="upload_info">
			<div class="contentProgress" style="width:108px; margin-top:3px;"><div class="progress barO" style="width:{file_progress}%;"></div></div>
		</div>
		
		<div id="upload_error">
			<div><b>{file_name}</b> ({file_size})</div>
			<div class="error f12">Error: {file_error}</div>
		</div>
	</div>
	
</div>
