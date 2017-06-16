<?php
	$mr = [];

	$mr['list'] = function() use ($list, $pages_config)
	{
		ob_start();?>

		<?php $this->load->view('tpl::news/_common/list', array('list' => $list)); ?>
		<div class="clear"></div>

		<div class="auto_check_pages">
			<?php $this->widget->site->pages($pages_config); ?>
		</div>
		<div class="clear"></div>

		<?php return ob_get_clean();
	};

	echo macro('mr::box')->box([
		'title' => lang('title_news_list'),
		'body'  => $mr['list'](),
	]);