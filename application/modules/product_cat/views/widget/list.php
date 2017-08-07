<?php $type = $widget->setting['style']; ?>
<?php if ($type == 'default'): ?>
<div class="block-categories">
	<div class="block-title heading-opt1">
		<strong class="title"><?php echo $widget->name ?></strong>
	</div>
	<div class="block-content">
		<ul class="list-categories">
			<?php echo model('product_cat')->get_tree(["show_in_menu"=>1]); ?>
			<?php /*foreach($list as $row){
				//pr($row);
				$row = mod('product_cat')->add_info($row);
				if($row->icon_id)
					$icon = '<img src="'.$row->_icon->url.'" alt="img">';
				elseif($row->icon_fa){
					$icon = '<icon class="fa fa-'.$row->icon_fa.'"></icon> ';
				}
				else{
					$icon = '<icon class="fa fa-arrows"></icon> ';
				}

				$tree =!$row->parent_id?"parent":'child';
				?>
				<li class="<?php echo $tree ?>">
					<a href="<?php echo $row->_url_view ?>">
						<span class="icon"><?php echo $icon ?></span>
						<span class="text"><?php echo $row->name ?></span>
					</a>
					<span class="dropdown-toggle"><span>toggle</span></span>

				</li>
			<?php } */ ?>
		</ul>
	</div>
</div>
<?php elseif ($type == 'style1'): ?>

	<section class="work-team">
		<div class="container">
			<div class="heading"><?php echo $widget->name; ?></div>
			<div class="row">
				<?php
				foreach($list as $row){ //pr($row);
					$row = mod('product_cat')->add_info($row);
					if($row->image_id)
						$icon = '<img src="'.$row->image->url.'" alt="img">';
					elseif($row->icon_fa){
						$icon = '<icon class="fa fa-'.$row->icon_fa.'"></icon> ';
					}
					else{
						$icon = '<icon class="fa fa-arrows"></icon> ';
					}
					$tree =!$row->parent_id?"parent":'child';
					?>
					<div class="col-md-3 col-sm-4 col-xs-6">
						<div class="box">
							<div class="box1">
								<?php echo $icon ?>
								<span class="title"><?php echo $row->name ?></span>
							</div>
							<div class="box2">
								<div class="caption"><a href="<?php echo $row->_url_view ?>" title="<?php echo $row->name ?>"><?php echo character_limiter($row->brief,100) ?></a></div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php endif; ?>

