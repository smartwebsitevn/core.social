
<?php foreach ($list as $lv1): ?>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<a href="<?php echo $lv1->_url_view; ?>"
			><h3 class="panel-title">
				<span class="glyphicon glyphicon-expand"></span> 
				<?php echo $lv1->name; ?>
			</h3></a>
		</div>
		
		<ul class="list-group">
		
			<?php foreach ($lv1->_sub as $lv2): ?>
				<a href="<?php echo $lv2->_url_view; ?>"
					class="list-group-item <?php //if ($item->_is_active) echo 'active'; ?>"
				><?php echo $lv2->name; ?></a>
			<?php endforeach; ?>
			
		</ul>
	</div>

<?php endforeach; ?>
