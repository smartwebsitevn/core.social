
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
			<div class="col-md-4 col-xs-4 ">
				<a id="action_upload" class="">
					<i class="pe-7s-photo"></i>
					<span>Chia sẻ</span><br>
					<span>hình ảnh</span>
				</a>
			</div>
			<div class="col-md-4 col-xs-4 ">
				<a id="share-video" data-target="#modal_share_video" data-toggle="modal">
					<i class="pe-7s-film"></i>
					<span>Chia sẻ</span><br>
					<span>video Youtube</span>
				</a>
			</div>
			<div class="col-md-4 col-xs-4 ">
				<a id="upload-link"  data-target="#modal_share_link" data-toggle="modal" >
					<i class="pe-7s-exapnd2"></i>
					<span>Chia sẻ</span><br>
					<span>một đường link</span>

				</a>
			</div>
		</div>
	</div>

	<div class="clear"></div>
	
	
	<!-- Temp html -->
	<div id="temp" style="display:none">
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


