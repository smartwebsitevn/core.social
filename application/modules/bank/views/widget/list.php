
<div class="t-box box2">
	<div class="box-title">
		<h6><?php echo $widget->name; ?></h6>
	</div>
	
	<div class="box-content">
		
		<div class="list_item">
		
			<?php foreach ($list as $row): ?>
			
				<div class="item">
				
					<p><b><?php echo $row->name; ?></b></p>
					
					<p>Chủ TK: <?php echo $row->acc_name; ?></p>
					
					<p>Số TK: <?php echo $row->acc_id; ?></p>
					
				</div>
					
			<?php endforeach;?>
			
		</div>
		
		<a href="<?php echo site_url('bank'); ?>" class="right"
		><?php echo lang('button_view_all'); ?></a>
		
		<div class="clear"></div>
	</div>
</div>
