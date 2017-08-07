<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="block-testimonial">
				<div class="owl-carousel">
					<?php foreach($list as $row): ?>
						<div class="testimonial-item ">
							<div class="testimonial-image">
								<img src="<?php echo $row->image->url ?>" />
							</div>
							<div class="testimonial-content">
								<span class="testimonial-author"><?php echo $row->name ?> - <?php echo $row->regency ?></span>
								<div class="testimonial-quote">
									<?php echo $row->say ?>
								</div>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-share-facebook">
				<div class="fb-page"
					 data-href="<?php echo widget("site")->setting("facebook") ;// data-width="200" data-height="200"  data-tabs="timeline"?>"
					 data-adapt-container-width="true"

					 data-small-header="false"
					 data-hide-cover="false"
					 data-show-facepile="true">
				</div>
			</div>
		</div>
	</div>
</div>