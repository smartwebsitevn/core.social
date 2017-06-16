<div class="block-categories">
	<div class="block-title heading-opt1">
		<strong class="title"><?php echo $widget->name ?></strong>
	</div>
	<div class="block-content">
		<ul class="list-categories">
			<?php echo model('product_cat')->get_tree_custom(["show_in_menu"=>1]); ?>
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


