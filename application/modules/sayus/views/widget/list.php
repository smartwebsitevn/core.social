<div class="ykienhocvien">
	<div class="line-content"> <span><?php echo $widget->name ?></span> </div>
	<div class="row">
		<?php foreach($list as $row){ ?>
			<div class="col-lg-1 col-sm-2 col-xs-4 text-center-kyna">
				<img src="<?php echo $row->image->url ?>" alt="hello" class="img-circle">
			</div>
			<div class="col-lg-5 col-sm-10 col-xs-8">
				<div class="title-client"><?php echo $row->name ?></div>
				<p class="text-client"><?php echo $row->say ?></p>
			</div>
		<?php } ?>
	</div>
</div>