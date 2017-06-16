<?php if($info->cat_id): ?>
<div class="block-postRelated2 ">
    <div class="block-title heading-opt1">
        <strong class="title">Cùng thể loại</strong>
    </div>
    <div class="block-content">
        <div class="owl-carousel carousel-khoahoc3">
            <?php widget('product')->same_cat($info->cat_id, [], 'slide'); ?>
        </div>
    </div>
</div>
<?php endif; ?>