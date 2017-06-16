<?php ob_start(); ?>

<div>
			<div class="videoproduct">
				<!-- Tab panes -->
				<div class="tab-content">
					<?php $i=1;
					foreach($info->video as $video){?>
						<div role="tabpanel" class="tab-pane<?php if($i==1){?> active<?php }?>" id="video_tab_<?php echo $i?>">
							<div class="embed-responsive embed-responsive-16by9 boxplayyoutube">
								<iframe class="embed-responsive-item" frameborder="0" allowfullscreen="" src="https://www.youtube.com/embed/<?php echo getIdYouTube($video) ?>"></iframe>
							</div>
						</div>
						<?php $i++;} ?>
				</div>
				<!-- Nav tabs -->
				<div class="nav nav-tabs row" role="tablist">
					<?php $i=1;
					foreach($info->video as $video){
						$img = getImgYouTube($video);?>
						<div role="presentation" class="col-xs-4 col-sm-2"><a href="#video_tab_<?php echo $i?>" aria-controls="video_tab_<?php echo $i?>" role="tab" data-toggle="tab">
								<img src="<?php echo $img ?>" alt="<?php echo $info->name ?>" />
							</a></div>
						<?php $i++;} ?>
				</div>
			</div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs nav-tabs-product" role="tablist">
		<li role="presentation" class="active"><a href="#content" aria-controls="content" role="tab" data-toggle="tab"><?php echo lang("content")?></a></li>

		<li role="presentation"><a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><?php echo lang("comment")?></a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content product-content">
		<div role="tabpanel" class="tab-pane active" id="content">
			<?php echo handle_content($info->content, 'output') ?>
		</div>

		<div role="tabpanel" class="tab-pane active" id="comment">
			<div class="fb-comments" data-href="<?php echo current_url() ?>" data-numposts="10" data-width="100%"></div>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId=1600132253579340";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
		</div>
	</div>

</div>


<?php $body = ob_get_clean(); ?>

<?php
echo macro()->box([
		'name' => $info->name,
		'body' => $body,
]);
?>


<?php
ob_start();
$where = array();
$where['where']['id !='] =$info->id;
$where['limit'] = array(5);
widget($class)->_list($where);
$body = ob_get_clean();
echo macro()->box([
		'name' => lang("video"),
		'body' => $body,
]);
?>
