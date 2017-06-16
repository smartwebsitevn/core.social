<div class="product-info-detailed">
    <div class="text-center mb20">
        <?php if (isset($info->link_demo) && $info->link_demo): ?>
            <a class="btn btn-default" target="_blank" title='Demo' href="<?php echo  $info->_url_demo?>">
                Xem Demo Website
            </a>
        <?php endif; ?>

        <?php widget('product')->action_favorite($info, 'favorite_big') ?>
    </div>

    <div class="block-title sticky-element anchor-element">
        <ul>
            <?php if (trim($info->description)):?>
            <li><a href="#0" class="active" data-pos="#product_info_intro">Mô tả</a></li>
            <?php endif; ?>

            <?php if (trim($info->technical)): ?>
                <li><a href="#0" data-pos="#product_info_technical">Thông số</a></li>
            <?php endif; ?>
            <?php if (trim($info->note)): ?>
                <li><a href="#0" data-pos="#product_info_note">Ghi chú</a></li>
            <?php endif; ?>
            <?php if ($info->files): ?>

                <li><a href="#0" data-pos="#product_info_files">Tệp đính kèm</a></li>
            <?php endif; ?>
            <?php if ($info->manufacture_id): ?>
                <li><a href="#0" data-pos="#product_info_manufacture">Nhà sản xuất</a></li>
            <?php endif; ?>

            <?php if (mod("product")->setting('comment_allow') || mod("product")->setting('comment_fb_allow') || mod("product")->setting('setting[rate_allow]')): ?>
                <li><a href="#0" data-pos="#product_info_comment">Đánh giá</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="block-content ">
        <?php if (trim($info->description)):?>
        <div id="product_info_intro" class="block-info">
            <?php t('view')->load('tpl::product/_common/info_intro') ?>
        </div>
        <?php endif; ?>

        <?php if (trim($info->technical)): ?>
            <div id="product_info_technical" class="block-info">
                <?php t('view')->load('tpl::product/_common/info_technical') ?>
            </div>
        <?php endif; ?>

        <?php if (trim($info->note)): ?>
            <div id="product_info_note" class="block-info">
                <?php t('view')->load('tpl::product/_common/info_note') ?>
            </div>
        <?php endif; ?>

        <?php if ($info->files): ?>
            <div id="product_info_files" class="block-info">
                <?php t('view')->load('tpl::product/_common/info_files'); ?>
            </div>
        <?php endif; ?>

        <?php if ($info->manufacture_id): ?>
            <div id="product_info_manufacture" class="block-info">
                <?php t('view')->load('tpl::product/_common/info_manufacture'); ?>
            </div>
        <?php endif; ?>
        <?php if (mod("product")->setting('comment_allow') || mod("product")->setting('comment_fb_allow') || mod("product")->setting('setting[rate_allow]')): ?>

            <div id="product_info_comment" class="block-info">
                <?php t('view')->load('tpl::product/_common/info_comment') ?>
            </div>
        <?php endif; ?>
    </div>
</div>


