<div class="type-info-detailed">
    <div class="block-content">
        <div id="tQuan" class="box-info-detailed">
            <?php t('view')->load('tpl::type/_common/info_intro') ?>
        </div>
        <div id="dGia" class="box-info-detailed">
            <?php t('view')->load('tpl::type/_common/info_comment') ?>
        </div>
    </div>
</div>

<div class="block-postRelated2 ">
    <div class="block-title heading-opt1">
        <strong class="title">Cùng danh mục</strong>
    </div>
    <div class="block-content">
        <div class="owl-carousel carousel-khoahoc3">
            <?php widget('type')->same_cat($info->cat_id, [], 'slide'); ?>
        </div>
    </div>
</div>