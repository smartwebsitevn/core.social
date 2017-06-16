<?php if (isset($list) && $list): ?>

	<?php foreach ($list as $row): ?>
		<div class="slide-block">
			<a href="<?php echo $row->_url_view; ?>" class="mask">
				<img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"/>
			</a>
			<div class="slide-block-body clearfix">
				<a href="<?php echo $row->_url_view; ?>"><?php echo $row->name; ?></a>

				<div class="block-body-left">
					<span>Giáo viên: <?php echo $row->_author_name ?></span><br/>
					<span>Học phí: <?php echo $row->_price ?></span>
				</div>
				<div class="block-body-right">
					<i class="fa fa-eye" aria-hidden="true"></i>
					<span><?php echo number_format($row->count_view) ?></span>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
    <div class="clear"></div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>