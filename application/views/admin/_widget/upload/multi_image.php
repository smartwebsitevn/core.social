
<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('#upload_<?php echo $_rd; ?>').pluploadScript({
			mod: 				'multi_image',
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


<style>
#upload_<?php echo $_rd; ?> .plupload_container {
	padding: 0px;
}
#upload_<?php echo $_rd; ?> .plupload_filelist_header, 
#upload_<?php echo $_rd; ?> .plupload_filelist, 
#upload_<?php echo $_rd; ?> .plupload_button.plupload_start {
	display: none;
}
#upload_<?php echo $_rd; ?> .plupload_filelist_footer {
	border: 1px solid #D5D5D5;
}
#upload_<?php echo $_rd; ?> [id$="_container"].plupload {
	display: none!important;
}
</style>


<div id="upload_<?php echo $_rd; ?>">
	<div id="file_list" _field="<?php echo $_rd; ?>_file_list" _url="<?php echo $upload_url['get']; ?>">
		<div id="<?php echo $_rd; ?>_file_list_show"></div>
		<div id="<?php echo $_rd; ?>_file_list_load" class="tab_load mt10 mb10"></div>
	</div>
	<div class="clear"></div>
	
	<div id="file_upload_<?php echo $_rd; ?>"></div>
	<div class="clear"></div>
	
	<div class="f11 formNote">
		<?php echo $this->lang->line('max_size'); ?>: <b><?php echo $upload_config['max_size']/1024; ?>Mb</b> - 
		<?php echo $this->lang->line('allowed_types'); ?>: <b><?php echo str_replace('|', ', ', $upload_config['allowed_types']); ?></b>
	</div>
</div>

