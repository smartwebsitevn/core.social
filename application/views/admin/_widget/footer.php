
<!-- Widget verify action -->
<?php $this->load->view('admin/_widget/verify_action');; ?>
<?php $this->load->view('admin/_widget/common/modal_system_notify'); ?>
<?php $this->load->view('admin/_widget/common/modal_verify_action'); ?>
<!-- Footer -->
<div class="footer">
<div class="footer-inner">
<!-- basics/footer -->
	<div class="footer-content">
		<?php
		$year_start = 2015;
		$year_cur 	= date('Y', now());
		$year 		= ($year_cur > $year_start) ? $year_start.'-'.$year_cur : $year_start;
		?>
		<?php echo lang('copyright_full',$year) ?>
	</div>
</div>
<!-- /basics/footer -->
</div>
<button type="button" id="back-to-top" class="btn btn-primary btn-sm back-to-top">
<i class="fa fa-angle-double-up icon-only bigger-110"></i>
</button>


<?php
$public_url=public_url();
$public_url_admin=public_url('admin');
$public_url_js=public_url('js');
?>

<!-- End custom CSS here -->
<!--[if lt IE 9]>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/html5shiv.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/pace/pace.min.js"></script>

<!-- PAGE LEVEL PLUGINS JS -->

<!-- JUI -->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery-ui.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery-ui.custom.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jqueryui/jquery.ui.touch-punch.min.js"></script>


<!-- form -->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/select2/select2.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/masked-input/jquery.maskedinput.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-markdown/markdown.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-markdown/bootstrap-markdown.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/datetime/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/fuelux/spinner.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-touchspin/bootstrap.touchspin.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/colorpickers/bootstrap-colorpicker.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/colorpickers/ek-colorpicker.js"></script>
<!-- table -->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/footable/footable.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/datatables/datatables.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/datatables/datatables.responsive.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/forms/jquery.tagsinput.min.js"></script>
<link rel="stylesheet"  href="<?php echo $public_url_admin; ?>/ekoders/css/plugins/forms/jquery.tagsinput.min.css" >
<!-- Themes Core Scripts -->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/main.js"></script>




<!-- REQUIRE FOR SPEECH COMMANDS -->
<!--<script src="<?php /*echo $public_url_admin; */?>/ekoders/js/speech-commands.js"></script>-->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/gritter/jquery.gritter.min.js"></script>

<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/lazyload/jquery.lazyload.min.js"></script>

<script type="text/javascript" src="<?php echo $public_url_js ?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/autosize/jquery.autosize.min.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/scrollTo/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/autonumeric/jquery.autonumeric.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/zclip/jquery.zclip.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/form/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/jquery.app.ui.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $public_url_js; ?>/jquery/colorbox/colorbox.css" media="screen" />

<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/script.js"></script>


<!-- initial page level scripts for examples -->
<!--  <script src="<?php /*echo $public_url_admin; */?>/ekoders/js/plugins/jqueryui/jquery-ui.init.min.js"></script>-->
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/slimscroll/jquery.slimscroll.init.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/footable/footable.init.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/plugins/datatables/datatables.init.js"></script>
<script src="<?php echo $public_url_admin; ?>/ekoders/js/form.init.js"></script>


<script type="text/javascript" src="<?php echo $public_url_js ?>/admin.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $public_url_admin; ?>/css/module.css" >
<script type="text/javascript" src="<?php echo $public_url_admin; ?>/js/module.js" type="text/javascript"></script>