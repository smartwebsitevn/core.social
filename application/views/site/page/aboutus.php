
<div class="container">
	<?php echo html_entity_decode($page->content); ?>
	<?php if ($list):
		$list1 = array_chunk($list,2);
		?>
	<?php foreach ($list1 as $list):  //pr($it);?>

	<div class="row">
			<?php foreach ($list as $it):  //pr($it);?>
				<div class="col-md-6 col-sm-6">
					<div class="item-quan-tri">
						<div class="item-img">
							<img src="<?php echo $it->image->url ?>" /></div>
						<div class="name"><?php echo $it->name ?></div>
						<div class="job"><?php echo $it->regency ?></div>
						<div class="description">
							<?php echo macro()->more_block($it->description,110); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>

	<?php endif; ?>
</div>
