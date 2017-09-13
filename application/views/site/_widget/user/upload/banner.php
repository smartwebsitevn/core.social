
<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		$('.upload_<?php echo $_rd; ?>').pluploadScript({
			mod: 				'banner',
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


<div id="banner_upload" class="upload_<?php echo $_rd; ?> ">
	<div class="upload_complete"></div>
	<div class="clear"></div>
	<div class="upload_info " style="display:none;"></div>
	<div class="clear"></div>
	<div class="upload_error" style="display:none;"></div>
	<div class="clear"></div>

	<div class="upload_action " style="margin-top:2px;">
		<a href="#0" id="action_upload">Cập nhật</a>  <a href="" id="action_del"  style="display:none" >| Xóa</a>
	</div>
	<div class="clear"></div>

	<!-- Temp html -->
	<div id="temp" style="display:none">
		<div id="upload_complete">
			<div class="file_image_single">
				<div class="file_image_single_img">
					<a href="{file_url}" onclick="lightbox(this); return false;"><i class="fa fa-camera"></i></a>
				</div>
			</div>
		</div>
		<div id="upload_info">
			{file_name}-{file_size}
			<div class="progress ">
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {file_progress}%;"></div>
			</div>

		</div>

		<div id="upload_error">
			<div><b>{file_name}</b> ({file_size})</div>
			<div class="error f12">Error: {file_error}</div>
		</div>
	</div>
</div>

