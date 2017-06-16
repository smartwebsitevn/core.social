	<meta charset="utf-8">

	<title><?php echo $title; ?></title>

	<meta name="title" content="<?php echo $title; ?>" />
	<meta name="description" content="<?php echo $description; ?>" />
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<meta name="robots" content="<?php echo $robots; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $meta_other; ?>

	<link href="<?php echo $icon ?>" rel="shortcut icon" type="image/x-icon"/>

	<!-- Add custom CSS here -->
	<link rel="stylesheet" href="<?php echo public_url('site/css/css.css') ?>">

	<!-- End custom CSS here -->

	<!-- Core Js -->
	<script src="<?php echo public_url('js/jquery/jquery.min.js') ?>"></script>
	<script src="<?php echo public_url('js/angular/angular.min.js') ?>"></script>
	<script src="<?php echo public_url('js/angular/angular-ng-modules/angular-ng-modules.js') ?>"></script>
	<style>
	.form-error{color:red;}
	.form-control{min-width:200px;max-width:350px;}
	.form-group{margin:10px 0px;}
	.btn-default{border:none;padding:7px 20px;background:#d15050;color:#fff;cursor:pointer}
	.param_custom .control-label, .param_custom .col-sm-9{display:inline-block}
	</style>

	