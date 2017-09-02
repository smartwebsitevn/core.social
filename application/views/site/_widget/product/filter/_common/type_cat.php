<?php
$type_cats = model('type_cat')->get_list_hierarchy([], ['show' => 1]);
echo macro()->filter_dropdown_category(['value' => $filter['type_cat_id'], 'values' => $type_cats, 'param' => 'type_cat_id', 'name' => lang('filter_category'), 'obj' => 'type_cat', 'attr' => ['data-ajax-filter' => true]]); ?>
<div class="ajax-filter"></div>