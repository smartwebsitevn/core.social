
<?php
$_data_sort = function () use ($filter, $total_rows, $sort_orders, $sort_order) {
    ob_start();
    $sort_orders_data = [];
    foreach ($sort_orders as $v) {
        $sort_orders_data[$v] = lang('ordering_' . $v);
    }

    ?>
    <!--<input type="hidden" name="limitstart"/>-->
    <?php if (isset($filter['input_hidden']) && $filter['input_hidden']): ?>
        <?php foreach ($filter['input_hidden'] as $n => $v): ?>
            <input type="hidden" name="<?php echo $n; ?>" value="<?php echo $v ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="block-sorter">
        <?php
        echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data, 'values_opts' => ['value_required' => true], 'param' => 'order', 'name' => lang('ordering_' . $sort_orders[0]), 'class' => 'sort-dropdown']); ?>
    </div>

    <?php return ob_get_clean();
} ?>


<?php
    $sort_orders_data = [];
    foreach ($sort_orders as $v) {
        $sort_orders_data[$v] = lang('ordering_' . $v);
    }

    ?>
    <!--<input type="hidden" name="limitstart"/>-->
    <?php if (isset($filter['input_hidden']) && $filter['input_hidden']): ?>
        <?php foreach ($filter['input_hidden'] as $n => $v): ?>
            <input type="hidden" name="<?php echo $n; ?>" value="<?php echo $v ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="block-sorter">
        <?php
        echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data, 'values_opts' => ['value_required' => true], 'param' => 'order', 'name' => lang('ordering_' . $sort_orders[0]), 'class' => 'sort-dropdown']); ?>
    </div>

