<div class="row">
	<?php $class= 'col-xs-6 col-sm-3';
	if(isset($clas))
		$class = $clas;
	foreach($list as $row){ ?>
		<div class="<?php echo $class ?>">
			<div class="box-download">
				<div class="images">
					<a<?php if($row->nofollow){?> rel="nofollow"<?php }?> href="<?php echo $row->_url_view ?>"><img src="<?php echo $row->image->url ?>" alt="images" class="img-responsive" /></a>
				</div>
				<h3 class="title">
					<a<?php if($row->nofollow){?> rel="nofollow"<?php }?> href="<?php echo $row->_url_view ?>"><?php echo $row->name ?></a>
				</h3>

				<div class="row">
					<!--<div class="col-xs-8">
						<?php /*echo lang("total_size")*/?>: <?php /*echo $row->_total_sizes */?> | <?php /*echo $row->total_files */?> <?php /*echo lang("files")*/?>
					</div>-->
					<div class="col-xs-12 text-center">
						<button class="btn btn-sm" data-modal="<?php echo $row->_url_download ?>"><span class="fa fa-download" aria-hidden="true"></span> <?php echo lang("btn_download")?></button>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<?php if(isset($pages_config))
	$this->widget->site->pages($pages_config); ?>