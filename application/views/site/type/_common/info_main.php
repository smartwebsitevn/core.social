<h1 class="page-title">
    <?php echo $info->name ?>
</h1>

<?php // echo view('tpl::_widget/type/display/item/info_rate', array('info' => $info)); ?>
<div class="type-meta">
    <p>
        Chuyên mục: <?php echo $info->_cat_name ?>  &nbsp;&nbsp;
        Cập nhập: <?php echo $info->_updated ?> &nbsp;&nbsp;
        Lượt xem: <?php echo $info->count_view ?></p>
    </p>
</div>

<?php echo widget("media")->player($info->video_data,['image_url'=>$info->banner->url],'player') ?>
