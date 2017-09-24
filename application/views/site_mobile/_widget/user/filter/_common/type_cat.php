<?php echo  macro()->modal_start(['id'=>'modal-user-filter','name'=>'Lọc kết quả']); ?>
<div class="product-total">
    Có <span  class="ajax-content-product-total "><?php echo isset($total_rows) ? number_format($total_rows) : '...' ?></span> kết quả
</div>
<?php
$type_cats = model('type_cat')->get_list_hierarchy([], ['show' => 1]);
echo macro()->filter_dropdown_category(['value' => $filter['type_cat_id'], 'values' => $type_cats, 'param' => 'type_cat_id', 'name' => lang('filter_category'), 'obj' => 'type_cat', 'attr' => ['data-ajax-filter' => true]]); ?>
<div class="ajax-filter"></div>

<div class="clearfix"></div>
<a href="#0" class="btn btn-default mr10 " data-dismiss="modal" aria-label="Close"> Xem kết quả</a>
<a href="#0" class="btn btn-outline  btn-clear-all" style="display: none">Xóa bộ lọc</a>
<?php echo macro()->modal_end(); ?>

