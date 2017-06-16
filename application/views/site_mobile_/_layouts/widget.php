<!DOCTYPE html>
<html>
<head>
	<?php
	$asset = public_url() ;
	$asset_theme =$asset. '/site/theme/';
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/responsive.css">
</head>
<body >
	<?php echo $widget; ?>
	<!-- E_MOVIE-->
	<script type="text/javascript" src="<?php echo $asset_theme ?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $asset_theme ?>js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo $asset_theme ?>js/owl.carousel.js"></script>
	<script type="text/javascript" src="<?php echo $asset_theme ?>js/main.js"></script>
</body>

</html>

