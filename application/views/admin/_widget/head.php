<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
$public_url=public_url();
$public_url_admin=public_url('admin');
$public_url_js=public_url('js');
?>
<title><?php echo $title ?></title>

<meta name="robots" content="noindex, nofollow" />

<!-- Icon -->
<link href="<?php echo $icon//public_url('site/images/icon.png'); ?>" rel="shortcut icon" type="image/x-icon"/>

  	<!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/fonts.css">
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/font-awesome/css/font-awesome.min.css">


	
	<!-- PAGE LEVEL PLUGINS STYLES -->
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/jqueryui/jquery-ui.min.css" />
  	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
	
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/bootstrap-datepicker/datepicker.css">
	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/datetime/bootstrap-datetimepicker.min.css">
  	<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/footable/footable.min.css">

	<link rel="stylesheet"  href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/select2/select2.css" rel="stylesheet">
	<link href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">

  
	<!-- REQUIRE FOR SPEECH COMMANDS -->
	<link rel="stylesheet" type="text/css" href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/gritter/jquery.gritter.css" />

	
	<!-- Theme CSS -->
	<link id="qstyle" rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/themes/style-smart.css">
	
    <!-- Add custom CSS here -->
<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/ekoders/css/themes/custom.css">

<link rel="stylesheet" type="text/css" href="<?php echo $public_url_admin; ?>/css/css.css" media="screen" />


    
<script type="text/javascript">
	var admin_url 	= '<?php echo admin_url('', array('suffix' => FALSE)) ?>/';
	var base_url 	= '<?php echo base_url() ?>';
	var public_url 	= '<?php echo public_url() ?>/';
</script>

<script src="<?php echo $public_url_admin; ?>/ekoders/js/jquery.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/bootstrap.min.js"></script>
<script src="<?php echo public_url('js/angular/angular.min.js') ?>"></script>
<script src="<?php echo public_url('js/angular/angular-ng-modules/angular-ng-modules.js') ?>"></script>
