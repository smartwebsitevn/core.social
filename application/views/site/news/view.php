<?php  echo macro()->page_heading($news->title)?>
<?php echo macro()->page_body_start()?>
<p class="text-muted">
	<?php echo $news->_created;//_created_time ?> |
	<?php echo lang('count_view'); ?>: <?php echo $news->count_view; ?>
</p>

<p>
	<b><?php echo $news->intro; ?></b>
</p>

<p class="text-justify">
	<?php echo html_entity_decode($news->content); ?>
</p>
<?php echo macro()->page_body_end()  ?>

<?php echo widget('news')->cat_news($news->cat_news_id, $news->id); ?>


