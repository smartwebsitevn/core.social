
<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('.upload_<?php echo $_rd; ?>').pluploadScript({
			mod: 				'media',
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


<div class="upload_<?php echo $_rd; ?> ">
	<div id="file_list" class="file_list_media" _field="<?php echo $_rd; ?>_file_list" _url="<?php echo $upload_url['get']; ?>">
		<div id="<?php echo $_rd; ?>_file_list_show"></div>
		<div id="<?php echo $_rd; ?>_file_list_load" class="tab_load mt10 mb10"></div>
	</div>
	<div class="clear"></div>

	<div class="upload_info " style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_error" style="display:none;"></div>
	<div class="clear"></div>
	
	<div class="upload_action " style="margin-top:2px;">
		<div class="upload-medias">
			<div class="col-md-6  text-right">
				<a id="action_upload" >
					<i class="pe-7s-photo"></i>
					<span>Upload hình</span><br>
				</a>
			</div>
			<div class="col-md-6  text-left">
				<a id="share-video" data-target="#<?php echo    'modal_share_video' ?>" data-toggle="modal">
					<i class="pe-7s-film"></i>
					<span>Chia sẻ Video</span><br>
				</a>
			</div>

		</div>
	</div>

	<div class="clear"></div>
	
	
	<!-- Temp html -->
	<div id="temp" style="display:none">
		<div id="upload_info">
			<div class="contentProgress" style="width:108px; margin-top:3px;"><div class="progress barO" style="width:{file_progress}%;"></div></div>
		</div>
		
		<div id="upload_error">
			<div><b>{file_name}</b> ({file_size})</div>
			<div class="error f12">Error: {file_error}</div>
		</div>
	</div>
	
</div>


