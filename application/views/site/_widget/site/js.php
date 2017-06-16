<?php
$asset = public_url() ;
$asset_js =$asset. '/js';
$asset_theme =$asset. '/site/theme/';
?>
<!-- Js -->
<script type="text/javascript">
	var site_url 	= '<?php echo site_url() ?>';
	var base_url 	= '<?php echo base_url() ?>';
	var public_url 	= '<?php echo public_url() ?>/';
	<?php if(!t('input')->is_mobile()):?>
	var viewport 	= 'desktop';
	<?php else:?>
	var viewport 	= 'mobile';
	<?php endif;?>

</script>

<!-- B_Style-->
<script type="text/javascript" src="<?php echo $asset_theme ?>/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo $asset_theme ?>/js/main.js"></script>
<!-- B_THEME-->
<script type="text/javascript" src="<?php echo $asset_theme ?>/js/product.js"></script>

<?php if (isset($js) && $js): ?>
	<?php if (is_array($js)): ?>
		<?php foreach ($js as $j): ?>
			<script src="<?php echo $asset_theme ?>js/<?php echo $j ?>.js"></script>
		<?php endforeach; ?>
	<?php else: ?>
		<script src="<?php echo $asset_theme ?>js/<?php echo $js ?>.js"></script>
	<?php endif; ?>
<?php endif; ?>
<!-- E_THEME-->


<!-- B_MOVIE-->
<link rel="stylesheet" type="text/css" href="<?php echo $asset_js ?>/movie/movie.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $asset_js ?>/movie/movie.responsive.css" />
<script type="text/javascript" src="<?php echo $asset_js ?>/movie/movie.play.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/movie/movie.show.js"></script>
<!-- E_MOVIE-->
<script type="text/javascript" src="<?php echo $asset_js ?>/browser_selector.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/underscore-min.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/slimscroll/slimscroll.min.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/select2/select2.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/autonumeric/jquery.autonumeric.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/autosize/jquery.autosize.min.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/form/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/scrollTo/jquery.scrollTo.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/sticky/jquery.sticky.js"></script>

<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/gritter/jquery.gritter.js"></script>
<link   type="text/css" href="<?php echo $asset_js ?>/jquery/gritter/jquery.gritter.css" rel="stylesheet" />

<!-- Rate -->
<link type="text/css" href="<?php echo $asset_js ?>/jquery/rate/rateit.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/rate/jquery.rateit.js"></script>
<!-- End Rate -->

<script type="text/javascript" src="<?php echo $asset_js ?>/jquery/jquery.app.ui.js"></script>
<script type="text/javascript" src="<?php echo $asset_js ?>/site.js"></script>
<?php /* // Cong thuc toan hoc, site nao can moi bat len ?>
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<?php */?>
<?php  echo $embed_js; ?>


<?php  if(isset($banner_run_left) && $banner_run_left):?>
	<div class="banner-left"><?php echo $banner_run_left;  ?></div>
<?php endif; ?>
<?php if(isset($banner_run_right) && $banner_run_right) :?>
	<div class="banner-right"><?php echo $banner_run_right;  ?></div>
<?php endif;  ?>
<?php if(isset($banner_popup) && $banner_popup) :?>
	<div class="banner-popup"><?php echo $banner_popup;  ?></div>
<?php endif; ?>
<?php /* ?>
 <?php $page_support = mod('page')->get_info(2) ?>
<?php $page_tech = mod('page')->get_info(3) ?>
	<div class="sidebar-fixted">
		<div class="action-close"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div>
		<a href="<?php echo $settings['facebook'] ?>" target="_blank" class="sb-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
		<a href="<?php echo $page_support->_url_view ?>"  class="sb-help"><i class="fa fa-commenting" aria-hidden="true"></i><span><?php echo $page_support->title ?></span></a>
		<a href="<?php echo site_url('movie_list/home') ?>"  class="sb-secrch"><span class="icon"></span><span>Tìm ki?m, L?c phim</span></a>
		<a href="<?php echo $page_tech->_url_view ?>" class="sb-film"><span class="icon"></span><span><?php echo $page_tech->title ?></span></a>
	</div>
<?php */ ?>
<?php //view('tpl::_widget/common/back_to_top') ;?>
<?php view('tpl::_widget/common/modal_blank') ;?>
<?php view('tpl::_widget/common/modal_balance_deposit') ;?>
<?php view('tpl::_widget/common/modal_system_notify') ;?>
<?php view('tpl::_widget/common/modal_verify_action'); ?>
<?php //view('tpl::_widget/common/modal_report_common'); ?>
<?php view('tpl::_widget/common/modal_player'); ?>
<?php widget('site')->message(); ?>
<?php if(!user_is_login()):?>
	<?php view('tpl::_widget/common/modal_login_require') ;?>
	<?php //view('tpl::_widget/common/modal_login') ;?>
	<?php view('tpl::user/combo1/combo_modal'); ?>

<?php endif; ?>
<?php  widget("product")->cart(null,'cart_modal');
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3";
		// them appId app se xuat hien them link ma app tro den trong phan share, like
		//js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3&appId=155243444672694";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

