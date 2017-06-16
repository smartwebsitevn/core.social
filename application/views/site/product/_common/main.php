<div class="product-media block-info">

    <h1 class="page-title">
        <?php echo $info->name ?>
    </h1>

    <div class="product-overview">
        <?php echo $info->brief ?>
    </div>
    <div class="product-meta">
        <p>
            <?php echo view('tpl::_widget/product/display/item/info_rate', array('info' => $info)); ?>

            <?php /* ?>
            Giảng viên:
            <a href="#gVien" target="_self" class="instructor-links__link">
                <?php //echo $info->_author_name ?>
            </a>


            - <?php echo lang("last_update") ?>&nbsp;: <?php echo get_date($info->updated) ?>
     <?php */ ?>
        </p>
    </div>
    <?php //t('view')->load('tpl::product/_common/info_video') ?>
    <div>
        <!-- Nav tabs -->

            <?php t('view')->load('tpl::product/_common/info_images') ?>

    </div>
    <div class="product-attribute row mt20">
        <div class="col-md-7">
            <?php t('view')->load('tpl::product/_common/info_attribute') ?>
        </div>
        <div class="col-md-5 text-right">
            <?php t('view')->load('tpl::product/_common/info_price') ?>
            <?php echo number_format($info->count_view) . ' lượt xem' ?>
            <?php t('view')->load('tpl::product/_common/share') ?>
        </div>
    </div>
</div>
