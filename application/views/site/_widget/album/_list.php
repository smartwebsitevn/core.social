<div class="row">
	<?php $class= 'col-sm-6 col-md-4';
	if(isset($clas))
		$class = $clas;
	foreach($list as $row){ ?>
		<div class="<?php echo $class ?>">
			<div class="box-picture">
				<div class="images">
					<a<?php if($row->nofollow){?> rel="nofollow"<?php }?> href="<?php echo $row->_url_view ?>"><img src="<?php echo $row->image->url ?>" alt="images" /></a>
				</div>
				<h3 class="title">
					<a<?php if($row->nofollow){?> rel="nofollow"<?php }?> href="<?php echo $row->_url_view ?>"><?php echo $row->name ?></a>
				</h3>
				<span class="date"><i class="fa fa-calendar"></i> <?php echo $row->_created ?></span>
				<a <?php if($row->nofollow){?> rel="nofollow"<?php }?> href="<?php echo $row->_url_view ?>"><i  class="fa fa-search-plus"></i></a>
			</div>
		</div>
	<?php } ?>
</div>

<?php if(isset($pages_config))
	$this->widget->site->pages($pages_config); ?>