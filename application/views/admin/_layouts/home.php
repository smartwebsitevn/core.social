<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php //	$this->load->view('tpl::_widget/head');	?>
	<?php widget('admin')->head(); ?>
</head>

<body>
<div id="wrapper">
<div id="main-container">
	<?php	$this->load->view('tpl::_widget/header');	?>

<!-- BEGIN MAIN PAGE CONTENT -->
<div id="page-wrapper">
	
	<!-- START YOUR CONTENT HERE -->
    <?php echo $content; ?>
	<!-- END YOUR CONTENT HERE -->
	
	<!-- BEGIN FOOTER CONTENT -->
	<?php view('tpl::_widget/footer'); ?>

	<?php
	$public_url_admin=public_url('admin');
	?>
	<!-- HOME -->

	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/easypiechart/jquery.easypiechart.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/easypiechart/excanvas.compiled.js"></script>


	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/easypiechart/jquery.easypiechart.min.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/easypiechart/excanvas.compiled.js"></script>

	<script src="<?php echo $public_url_admin; ?>/ekoders/js/home-page.init.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jquery-sparkline/jquery.sparkline.init.js"></script>
	<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/easypiechart/jquery.easypiechart.init.js"></script>
	<!-- END FOOTER CONTENT -->
	
</div><!-- /#page-wrapper -->	  
<!-- END MAIN PAGE CONTENT -->
	
</div>	
</div>
</body>
</html>