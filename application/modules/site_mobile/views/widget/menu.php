
<?php $type = $widget->setting['type']; ?>


<?php if ($type == 'top'): ?>
	
	<div class="menu grid_9">
		<ul class="mainmenu">
		
			<?php foreach ($items as $i => $item): ?>
			
				<li><a href="<?php echo $item->url; ?>"
					class="<?php if ($item->_is_active) echo 'active'; ?>"
					<?php if ($item->nofollow) echo 'rel="nofollow"'; ?>
				><?php echo $item->title; ?></a></li>
				
			<?php endforeach; ?>
			
		</ul>
	</div>

	
<?php elseif ($type == 'service'): ?>


	<?php $icons = array('database', 'cc-discover', 'credit-card', 'download', 'umbrella', 'trophy'); ?>
	
	<div class="cat-pro-list module-bg">
	
		<h3 class="title title-module line-color"
		><?php echo $widget->name; ?></h3>
		
		<ul class="list_0">
			<?php foreach ($items as $i => $item): ?>
			
				<?php $icon = array_get($icons, $i, 'reorder'); ?>
		
				<li class="item">
					<a href="<?php echo $item->url; ?>"
						class="<?php if ($item->_is_active) echo 'active'; ?>"
					><i class="fa fa-<?php echo $icon; ?>"></i><?php echo $item->title; ?></a>
				</li>
					
			<?php endforeach; ?>
		</ul>
	</div>

	
<?php elseif ($type == 'footer'): ?>

	
	<div class="footer-menu-wrapper">
		<div class="container_12">
			
			<ul class="footer-menu">
		
			<?php foreach ($items as $i => $item): ?>
			
				<li><a href="<?php echo $item->url; ?>"
					class="<?php if ($item->_is_active) echo 'active'; ?>"
					<?php if ($item->nofollow) echo 'rel="nofollow"'; ?>
				><?php echo $item->title; ?></a></li>
				
			<?php endforeach; ?>
			
			</ul>
			
		</div>
	</div>
	
	
<?php endif; ?>
