<div class="footer">

	<div class="footer-menu-wrapper">
		<div class="container_12">
			
			<ul class="footer-menu">
		
			<?php foreach ($menu_items as $item): ?>
			
				<li><a href="<?php echo $item->url; ?>"
					class="<?php if ($item->_is_active) echo 'active'; ?>"
					<?php if ($item->nofollow) echo 'rel="nofollow"'; ?>
				><?php echo $item->title; ?></a></li>
				
			<?php endforeach; ?>
			
			</ul>
			
		</div>
	</div>
	
	
	<div class="footer-info">
	
		<div><?php echo module_get_setting('site', 'name'); ?></div>
		<div>Địa chỉ: <?php echo module_get_setting('site', 'address'); ?></div>
		<div>Email: <?php echo module_get_setting('site', 'email'); ?></div>
		
		<div class="social">
		
			<?php foreach (array('facebook', 'twitter', 'google') as $p):
			
				$icon = array_get(array(
					'google' => 'google-plus',
				), $p, $p);
			?>
			
				<a href="<?php echo module_get_setting('site', $p); ?>" class="icon32"
					target="_blank"
				><i class="fa fa-<?php echo $icon; ?>"></i></a>
				
			<?php endforeach; ?>
			
		</div>
		<div> <?php echo $widget->setting['copyright']; ?> A design by NENCER</div>
	
	</div>
	
</div>


<!-- Js -->
<?php echo $widget->setting['js']; ?>
