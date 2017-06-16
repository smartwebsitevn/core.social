
<div class="list_news">
	<?php foreach ($list as $row): ?>
		<div class="item_news">
			<div class="col-xs-12 col-sm-3 col-lg-1">
				<a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->title; ?>">
					<img class="item_img" src="<?php echo $row->image->url_thumb; ?>" alt="<?php echo $row->title; ?>">
				</a>
			</div>
			
			<div class="col-xs-12 col-sm-9 col-lg-9">  
    			<div class="item_name link">
    				<a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->title; ?>">
    					<b><?php echo $row->title; ?></b>
    				</a>
    			</div>
    				
    			<div class="item_time">
    				<?php echo $row->_created;//$row->_created_time ?>
    			</div>
    			
    			<div class="item_content">
    				<?php echo $row->intro; ?>
    			</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	<?php endforeach; ?>
</div>