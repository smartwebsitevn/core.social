
<div class="grid_8">

	<div class="slide-bg">
		<div class="slider">
			<div class="camera_wrap camera_white_skin" id="camera_wrap_1">
			
				<?php foreach ($items as $i => $item): ?>
		            <div data-thumb="<?php echo $item->image->url; ?>"
		            	data-src="<?php echo $item->image->url; ?>"
		            ></div>
				<?php endforeach; ?>
	            
	        </div><!-- #camera_wrap_1 -->
			<!-- end slider -->
		</div>
	</div>
	
</div>
