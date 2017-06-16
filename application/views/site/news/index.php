<?php
echo macro()->page_heading( lang('title_news_list'))?>
<?php echo macro()->page_body_start()?>
<?php $this->load->view('tpl::news/_common/list', array('list' => $list)); ?>
<div class="clear"></div>

<div class="auto_check_pages">
	<?php $this->widget->site->pages($pages_config); ?>
</div>
<div class="clear"></div>
<?php echo macro()->page_body_end()  ?>
