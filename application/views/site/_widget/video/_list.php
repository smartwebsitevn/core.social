
<div class="row productbox">
	<?php foreach($list as $row){ ?>
		<div class=" col-xs-12 col-sm-6 col-md-3">
			<div class="product-box">
				<div class="images">
					<a href="<?php echo $row->_url_view ?>" <?php if($row->nofollow){?>rel="nofollow" <?php }?>>
						<img class="img-responsive" src="<?php echo $row->image->url ?>" alt="<?php echo $row->name ?>"/>
					</a>
				</div>
				<div class="info">
					<h3 class="title">
						<a <?php if($row->nofollow){?>rel="nofollow" <?php }?>href="<?php echo $row->_url_view ?>"><?php echo $row->name ?></a>
					</h3>
					<div class="view-detail"><a <?php if($row->nofollow){?>rel="nofollow" <?php }?>href="<?php echo $row->_url_view ?>" class="btn btn-danger"><?php echo lang("view_more")?></a></div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
	<?php if(isset($pages_config))
		$this->widget->site->pages($pages_config); ?>
