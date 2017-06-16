<?php  echo macro()->page_heading(lang('title_question_answer'))?>
<?php echo macro()->page_body_start()?>
<div class="views-header">
	<div class="box-cau-hoi pull-right">
		<a href="<?php echo site_url('page/faq') ?>"><?php echo lang('faq') ?></a>
	</div>
</div>
<div class="clearfix mb30"></div>

<div class="views-content">
	<?php $this->load->view('tpl::question_answer/_common/list', array('list' => $list)); ?>
</div>

<div class="clearfix"></div>

<div class="auto_check_pages">
	<?php $this->widget->site->pages($pages_config); ?>
</div>
<div class="clear"></div>
<?php echo macro()->page_body_end()  ?>
