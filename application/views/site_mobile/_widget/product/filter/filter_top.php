<form class="ajax_form_filter" data-group="product-filter" event-hook="productFilter"
      action="<?php echo $action; ?>"
      method="get">
    <div class="panel">
        <div class="panel-body">
            <?php t('view')->load('tpl::_widget/product/filter/_common/search') ?>
            <div class="block block-filter ">
                <div class="block-content clearfix">
                    <?php t('view')->load('tpl::_widget/product/filter/_common/total') ?>
                    <?php t('view')->load('tpl::_widget/product/filter/_common/sort') ?>
                    <?php t('view')->load('tpl::_widget/product/filter/_common/slider_point') ?>
                    <?php t('view')->load('tpl::_widget/product/filter/_common/reset') ?>
                    <?php t('view')->load('tpl::_widget/product/filter/_common/type_cat') ?>

                </div>
            </div>
        </div>
    </div>
</form>