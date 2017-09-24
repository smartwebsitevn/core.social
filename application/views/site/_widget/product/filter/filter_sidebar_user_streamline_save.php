<form class="ajax_form_filter" data-group="product-filter"  event-hook="productFilter"
      action="<?php echo current_url(); ?>"
      method="get">
    <?php t('view')->load('tpl::_widget/product/filter/_common/streamline_save') ?>
</form>