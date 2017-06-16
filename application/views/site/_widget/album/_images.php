<?php if($row->images){ ?>
	<section id="slider-service">
		<div id="slider-content" class="">
			<div data-ride="carousel" class="carousel slide" id="carousel-example-generic">
				<div class="carousel-inner">
					<?php $i=1;
					foreach($row->images as $item){;?>
						<div class="item <?php echo $i==1 ? 'active' : ''?>">

							<img alt="" src="<?php echo $item->image->url ?>" class="slide-image">

						</div>
						<?php $i++;} ?>
				</div>
				<a data-slide="prev" href="#carousel-example-generic" class="left carousel-control">
					<span class="fa fa-angle-left"></span>
				</a>
				<a data-slide="next" href="#carousel-example-generic" class="right carousel-control">
					<span class="fa fa-angle-right"></span>
				</a>
				<ol class="carousel-indicators">
					<?php $i=0;
					foreach($row->images as $item){?>
						<li <?php if($i==0){?>class="active" <?php }?>data-slide-to="<?php echo $i ?>" data-target="#carousel-example-generic"></li>
						<?php $i++;} ?>
				</ol>
			</div>
		</div>
	</section>
<?php } ?>