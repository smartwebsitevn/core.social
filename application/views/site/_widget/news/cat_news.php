<?php
	$mr = [];

	$mr['content'] = function() use ($list)
	{
		ob_start();?>

			
        <?php if (count($list)): ?>
        
        
        	<ul>
        		<?php foreach ($list as $row): ?>
        			<li style="margin-bottom:10px">
        				<a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->title; ?>">
        					- <?php echo $row->title; ?>
        				</a>
        			</li>
        		<?php endforeach; ?>
        	</ul>
        
        	
        <?php endif; ?>

		<?php return ob_get_clean();
	};

	echo macro('mr::box')->box([
		'title' => lang('title_news_other'),
		'content'  => $mr['content'](),
	]);
