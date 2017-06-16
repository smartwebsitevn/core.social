
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="glyphicon glyphicon-expand"></span> 
			<?php echo $widget->name; ?>
		</h3>
	</div>
	
	<ul class="list-group">
	
		<?php foreach ($list as $row): ?>
		
			<a href="<?php echo $row->_url_view; ?>"
				class="list-group-item <?php if ($row->_is_active) echo 'active'; ?>"
			><?php echo $row->name; ?></a>
			
		<?php endforeach; ?>
		
	</ul>
</div>
