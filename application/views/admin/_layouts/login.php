<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php
	$public_url=public_url();
	$public_url_admin=public_url('admin');
	$public_url_js=public_url('js');
	?>
	<title>Admin</title>

	<meta name="robots" content="noindex, nofollow" />

	<link rel="shortcut icon" href="<?php echo $public_url_admin; ?>/images/icon.png" type="image/x-icon"/>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/fonts.css">
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/font-awesome/css/font-awesome.min.css">

	<!-- PAGE LEVEL PLUGINS STYLES -->
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/jqueryui/jquery-ui.min.css" />

	<!-- REQUIRE FOR SPEECH COMMANDS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/gritter/jquery.gritter.css" />

	<!-- Theme CSS -->
	<link id="qstyle" rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/themes/style-smart.css">

	<!-- Add custom CSS here -->
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/themes/custom.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $public_url_admin; ?>/css/css.css" media="screen" />

	<!-- End custom CSS here -->
	<!--[if lt IE 9]>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/html5shiv.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/respond.min.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var csrf_token 	= '<?php echo csrf_token_hash() ?>';
		var admin_url 	= '<?php echo admin_url('', array('suffix' => FALSE)) ?>/';
		var base_url 	= '<?php echo base_url() ?>';
		var public_url 	= '<?php echo public_url() ?>/';
	</script>

	<!-- core JavaScript -->


	<script src="<?php echo $public_url_admin; ?>/ekoders/js/jquery.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/bootstrap.min.js"></script>

	<!-- PAGE LEVEL PLUGINS JS -->

	<!-- JUI -->
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery-ui.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery-ui.custom.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery.ui.touch-punch.min.js"></script>


	<!-- Themes Core Scripts -->

	<!-- REQUIRE FOR SPEECH COMMANDS -->
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/gritter/jquery.gritter.min.js"></script>
	<!-- initial page level scripts for examples -->

	<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/zclip/jquery.zclip.js"></script>
	<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/form/jquery.form.min.js"></script>
	<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/jquery.app.ui.js" type="text/javascript"></script>

	<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/colorbox/jquery.colorbox.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $public_url_js; ?>/jquery/colorbox/colorbox.css" media="screen" />

<script type="application/javascript">
	//####################################################
	// jQuery Handle
	//####################################################
	(function($)
	{

		$(document).ready(function()
		{
			// Form handle
			$('#form, .form_action').each(function()
			{
				var $this = $(this);
				$this.nstUI('formAction', {
					field_load: $this.attr('_field_load'),
					event_error: function(data)
					{
						// Reset captcha
						if (data['security_code'])
						{
							var captcha = $this.find('img[_captcha]').attr('id');
							if (captcha)
							{
								change_captcha(captcha);
							}
						}
					},
				});
			});
			// Placeholder
			$('input.placeholder').nstUI('placeholder');

		});
	})(jQuery);
	/**
	 * Thay doi captcha
	 */
	function change_captcha(field)
	{
		var t = jQuery('#'+field);
		var url = t.attr('_captcha')+'?id='+Math.random();
		t.attr('src', url);
		return false;
	}

</script>
</head>

  <body class="login">
	<div id="wrapper">
		<!-- BEGIN MAIN PAGE CONTENT -->
		<?php echo $content; ?>
		
	</div>
</body>
</html>