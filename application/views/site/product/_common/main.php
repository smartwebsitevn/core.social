<div class="product-media block-info">
    <h1 class="page-title">
        <?php echo $info->name ?>
    </h1>

    <div class="product-meta">
        <p>
            <?php echo view('tpl::_widget/product/display/item/info_rate', array('info' => $info)); ?>
        </p>
    </div>

    <div class="item-meta">
                            <span>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $info->id) . "?act=like" ?>"><i
                                    class="pe-7s-angle-up-circle"></i></a>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $info->id) . "?act=dislike" ?>"><i
                                    class="pe-7s-angle-down-circle"></i></a>
                            </span>
                                <span
                                    class="points"> <b><?php echo number_format($info->vote_total) ?></b> <?php echo lang("count_point") ?></span>
                            <span
                                class="views"> <b><?php echo number_format($info->view_total) ?></b> <?php echo lang("count_view") ?></span>
                            <span
                                class="comments"> <b><?php echo number_format($info->comment_count) ?></b> <?php echo lang("count_comment") ?></span>
        <span class="date_created"> <b><?php echo $info->_created ?></b> </span>

    </div>

    <?php //t('view')->load('tpl::product/_common/info_video') ?>
    <div>

        <?php t('view')->load('tpl::product/_common/info_images') ?>

    </div>
    <div class="item-overview">
        <?php echo macro()->more_word($info->description,63); ?>

    </div>
    <?php t('view')->load('tpl::product/_common/info_files') ?>


    <?php //t('view')->load('tpl::product/_common/info') ?>

    <?php //t('view')->load('tpl::product/_common/same_cat') ?>
    <?php t('view')->load('tpl::product/_common/info_comment') ?>

</div>
