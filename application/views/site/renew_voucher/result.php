<?php if(isset($products) && $products): ?>
<div class="panel panel-default ">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('product_list_apply_to_voucher') ?></h3>
	</div>
	<div class="panel-body">
		<div class="bl-bai-giang-noi-bat mt0">
           <?php view('tpl::_widget/product/display/list/list_default',array('list'=>$products));   ?>
        <?php /*if(isset($url_more) && $url_more): ?>
        <div class="views-more">
            <a href="<?php echo $url_more ?>">Xem thêm<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
        </div>
        <?php endif; */?>
	</div>
</div>
<?php endif; ?>
<?php if(isset($lessons) && $lessons): ?>
	<div class="panel panel-default ">
		<div class="panel-heading">
	<h3 class="panel-title"><?php echo lang('lesson_list_apply_to_voucher') ?></h3>
		</div>
	<div class="panel-body">
		<div class="bl-bai-giang-noi-bat  mt0">
			<?php view('tpl::_widget/lesson/display/list/list_default',array('list'=>$lessons));   ?>
			<?php /*if(isset($url_more) && $url_more): ?>
        <div class="views-more">
            <a href="<?php echo $url_more ?>">Xem thêm<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
        </div>
        <?php endif; */?>
		</div>
	</div>
<?php endif; ?>