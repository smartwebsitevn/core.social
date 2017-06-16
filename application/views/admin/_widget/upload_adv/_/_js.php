		<script type="text/javascript">		
		var base_url_site 	= '<?php echo public_url('site')?>/upload';		
        var sess_id = '<?php echo session_id(); ?>';
		var cTracker = '<?php echo get_time_file(); ?>';
		var uploaderMaxSize = <?php echo conver_file_size($config['maxfilesize']); ?>;
		var Maxnumberfiles = 10;
		var namebase = '<?php echo $_SERVER['HTTP_HOST']?>';
		var not_alow_type_file = /(\.|\/)(<?php echo $config['allowed_types']?>)$/i;
		var folder_id = <?php echo $folder_id?>;
		// alert
		var upfiledata = {
			note : {
				message_type_error : '<?php echo lang('message_type_error')?>',
				error_file_too_smail : '<?php echo lang('error_file_too_smail')?>',
				error_file_too_large : '<?php echo lang('error_file_too_large')?>',
				error_file_not_support : '<?php echo lang('error_file_not_support')?>',
				error_max_file : '<?php echo lang('error_max_file')?>',
				error_server_problem : '<?php echo lang('error_server_problem')?>',
				title_page : $('title').text(),
				error_please_enter_url : '<?php echo lang('error_please_enter_url')?>',
				error_no_valid_url : '<?php echo lang('error_no_valid_url')?>',
				error_add_max_url : '<?php echo lang('error_add_max_url', $config['maxfile'])?>',
				title_Speed : '<?php echo lang('title_Speed')?>',
				title_Remaining : '<?php echo lang('title_Remaining')?>',
				title_Progress : '<?php echo lang('title_Progress')?>',
				title_enter_url_file : '<?php echo lang('title_enter_url_file')?>',
			}
		}
        </script>



		<link rel="stylesheet" href="<?php echo public_url('site')?>/upload/themes/blue_v2/styles/screen.css" type="text/css" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo public_url('site')?>/upload/themes/blue_v2/styles/tabview-core.css" type="text/css" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo public_url('site')?>/upload/themes/blue_v2/styles/gh-buttons.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery-ui.js"></script>

		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/fusionCharts/JSClass/FusionCharts.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.tmpl.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/load-image.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/canvas-to-blob.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.iframe-transport.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.fileupload.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.fileupload-process.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.fileupload-resize.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.fileupload-validate.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/jquery.fileupload-ui.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/zeroClipboard/ZeroClipboard.js"></script>

		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/global.js"></script>
		<script type="text/javascript" src="<?php echo public_url('site')?>/upload/js/uploadhandler.js"></script>