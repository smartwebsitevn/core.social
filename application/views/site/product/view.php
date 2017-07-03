<div class="product-info-main detail-social">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="product-navigation">
                <div class="category">
                    ABD
                </div>
                <div class="next-page">
                    <?php if (isset($info_prev) && $info_prev): ?>
                            <a title="<?php echo $info_prev->name ?>" href="<?php echo $info_prev->_url_view ?>"><i class="pe-7s-angle-left-circle"></i></a>

                    <?php endif; ?>
                    <?php if (isset($info_next) && $info_next): ?>
                          <a title="<?php echo $info_next->name ?>"
                           href="<?php echo $info_next->_url_view ?>"><i class="pe-7s-angle-right-circle"></i></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <?php t('view')->load('tpl::product/_common/main') ?>


        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <?php //t('view')->load('tpl::product/_common/order') ?>
            <?php t('view')->load('tpl::product/_common/info_author') ?>
            <?php //t('view')->load('tpl::product/_common/info_video') ?>
            <?php t('view')->load('tpl::product/_common/same_author') ?>
        </div>

    </div>
</div>


