
<div class="partner">
	<div class="container_12">
		<ul>
		
		<?php foreach ($widget->setting['list'] as $val): ?>
			<li class="item text-center grid_2"><a>
				<img src="<?php echo upload_url($val); ?>">
			</a></li>
		<?php endforeach; ?>
		
		</ul>
	</div>
</div>