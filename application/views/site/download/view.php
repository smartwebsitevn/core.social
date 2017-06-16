<?php  echo macro()->page_heading($info->name)?>
<?php echo macro()->page_body_start()?>
<div class="pull-left newsitem">
	<div class="post-date">
		<i class="fa fa-calendar" aria-hidden="true"></i>: <?php echo $info->_created ?> | <?php echo lang("view")?>: <?php echo $info->view ?> |
		<?php echo lang("total_size")?>: <?php echo $info->_total_sizes ?> | <?php echo $info->total_files ?> <?php echo lang("files")?>
	</div>
	<?php view('tpl::_widget/addthis') ?>
</div>
<div class="pull-right">
	<button class="btn btn-sm btn-default" data-modal="<?php echo $info->_url_download ?>"><span class="fa fa-download" aria-hidden="true"></span> <?php echo lang("btn_download")?></button>
</div>
<div class="clearfix"></div>
<div class="content contents"><?php echo handle_content($info->content, 'output') ?></div>
<div class="clearfix"></div>
<?php if(isset($info->comment_status) && $info->comment_status){?>
	<h5 class="title_relative"><?php echo lang("comment")?></h5>
	<div class="fb-comments" data-href="<?php echo current_url() ?>" data-numposts="10" data-width="100%"></div>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId=1600132253579340";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
<?php }?>


<div class="box-tin-lien-quan">
	<h4><?php echo lang("news_other")?></h4>
	<div class="content row">

		<?php $where = array();
		$where['where']['id !='] =$info->id;
		$where['limit'] = array(4);
		widget($class)->_list($where); ?>
	</div>
</div>

<?php echo macro()->page_body_end()  ?>


