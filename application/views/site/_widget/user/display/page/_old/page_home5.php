<?php
//pr($list_feature);
?>
<div class="row">
    <?php view('tpl::_widget/user/display/list/list_home', array('list' => $list)); ?>
</div>

<?php if (isset($url_more) && $url_more): ?>
    <div class="text-center">
        <a href="<?php echo $url_more ?>" class="btn btn-default">Xem tất cả khóa học</a>
    </div>
<?php endif; ?>
<div class="clearfix"></div>