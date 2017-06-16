
<div class="container">
	<?php echo html_entity_decode($page->content); ?>
	<?php if ($list): ?>
		<div class="row">
			<?php foreach ($list as $it):  //pr($it);?>
				<div class="col-md-6 col-sm-6">
					<div class="item-quan-tri">
						<div class="item-img">
							<img src="<?php echo $it->image->url ?>" /></div>
						<div class="name"><?php echo $it->name ?></div>
						<div class="job"><?php echo $it->regency ?></div>
						<div class="descaption"><?php echo $it->description ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
