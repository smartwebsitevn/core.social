<?php
$_data_cat_type = function ($list, $name) use ($filter) {
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $row):
            $active_status = (isset($filter[$name]) && is_array($filter[$name]) && in_array($row->id, $filter[$name])) ? 1 : 0;
            ?>
            <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
                 data-name="<?php echo $name ?>[]" data-value="<?php echo $row->id ?>">
                <label><?php echo $row->name ?></label>
                <?php if ($active_status): ?>
                    <input name="<?php echo $name ?>[]" value="<?php echo $row->id ?>" type="hidden">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php return ob_get_clean();
};
$_data_array = function ($list, $name) use ($filter) {
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $v => $label):
            $active_status = (isset($filter[$name]) && $filter[$name] == $v) ? 1 : 0;
            ?>
            <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
                 data-name="<?php echo $name ?>[]" data-value="<?php echo $v ?>">
                <label><?php echo $label ?></label>
                <?php if ($active_status): ?>
                    <input name="<?php echo $name ?>[]" value="<?php echo $v ?>" type="hidden">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php return ob_get_clean();
};
$_data_single = function ($key, $value, $name) use ($filter) {
    ob_start();
    $active_status = (isset($filter[$key]) && $filter[$key]) == $value ? 1 : 0;
    ?>
    <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
         data-name="<?php echo $key ?>" data-value="<?php echo $value ?>">
        <label><?php echo $name ?></label>
        <?php if ($active_status): ?>
            <input name="<?php echo $key ?>" value="<?php echo $value ?>" type="hidden">
        <?php endif; ?>
    </div>
    <?php return ob_get_clean();
};
$_data_sort = function () use ($filter, $total_rows, $sort_orders, $sort_order) {
    ob_start();
    $sort_orders_data = [];
    foreach ($sort_orders as $v) {
        $sort_orders_data[$v] = lang('ordering_' . $v);
    }

    ?>
    <input type="hidden" name="limitstart"/>
    <?php if (isset($filter['input_hidden']) && $filter['input_hidden']): ?>
        <?php foreach ($filter['input_hidden'] as $n => $v): ?>
            <input type="hidden" name="<?php echo $n; ?>" value="<?php echo $v ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="block-sorter">
        <?php
        echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data, 'param' => 'order', 'name' => 'Mới nhất', 'class' => 'sort-dropdown']); ?>
    </div>

    <?php return ob_get_clean();
} ?>

<div class="panel">
    <div class="panel-body">
        <form id="form_filter_advance" class="ajax_form_filter" name="form_filter_advance" event-hook="productFilter"
              action="<?php echo $action; ?>"
              method="get">
            <div class="block block-search">
                <div class="block-content clearfix">
                    <div class="input-group">
                        <div class="select-search select-search-chosen">
                            <div class="select-container select-container-above select-container-focus2">
                                <ul class="select-rendered">
                                    <li class="select-icon">
                                        <i class="fa fa-search"></i>
                                    </li>
                                    <li class="select-input">
                                        <input type="text" class="select-input-field"
                                               placeholder="Tìm tìm tiêu đề hoặc hashtag #"
                                               id="select-input-field" name="name">
                                    </li>
                                    <?php /* ?>
                            <li class="select-placeholder">
                                <label
                                    for="select-input-field"><span><?php// echo lang("search_skill_or_cat_id_recruit") ?></span></label>
                            </li>
                            <li class="select-all-remove" data-placement="bottom" data-toggle="tooltip"
                                data-original-title="<?php echo lang("remove_all_search") ?>">
                                <span class="select-all-remove"></span>
                            </li>
                             <?php */ ?>

                                </ul>
                            </div>
                            <div class="select-container select-container-dropdown">
                                <div class="select-message">
                                    <span><?php echo lang("search_suggestions") ?></span>
                                    <!--<a href="#0" class="add-tab" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php /*echo lang("add_cat_recruit")*/ ?>"><?php /*echo lang("add_cat_recruit")*/ ?></a>
                                <a href="#0" calss="copy-tab" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php /*echo lang("copy_cat_from_my_brief")*/ ?>"><?php /*echo lang("copy_cat_from_my_brief")*/ ?></a>-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="block block-filter ">
                <div class="block-content clearfix">
                    <div class="block-total">
                <span
                    class="ajax-content-product-total "><?php echo isset($total_rows) ? number_format($total_rows) : '-' ?></span>
                        Kết quả
                    </div>
                    <div class="row">
                        <?php
                        $product_cats = model('type_cat')->get_list_hierarchy([], ['show' => 1]);
                        echo macro()->filter_dropdown_category(['value' => $filter['type_cat_id'], 'values' => $product_cats, 'param' => 'type_cat_id', 'name' => lang('filter_category'), 'obj' => 'type_cat', 'attr' => ['data-ajax-filter' => true]]); ?>
                        <div class="ajax-filter"></div>


                        <?php echo $_data_sort(); ?>

                        <div>
                            <input name="point" class="act-filter-slider" id="slider_point_hander" type="hidden"
                                   data-provide="slider"
                                   data-slider-min="0"
                                   data-slider-max="100"
                                   data-slider-step="10"
                                   data-slider-value="0" data-slider-tooltip="hide"/>

                            <div class="clearfix"></div>
                            <span id="slider_point">Trên <span id="slider_point_value">0</span> point</span>

                        </div>
                        <div class="action-filter">
                            <a href="#0" class="btn btn-default btn-xs mt20 btn-clear-all"><i class="pe-7s-close "
                                                             ></i> Reset bộ lọc</a>
                            <!--<a href="#0" class="btn btn-link btn-clear-all"><i class="pe-7s-lock"    style="font-size: 32px"></i></a>-->
                        </div>
                        <?php /* ?>
                        <div class=" act-filter-choice-group text-right">
                            <a class="act-filter-choice active" href="Javascript:;" data-name="layout" data-value="block">
                                <i class="pe-7s-menu icon "></i>
                            </a>
                            <a class="act-filter-choice" href="Javascript:;" data-name="layout" data-value="grid">
                                <i class="pe-7s-keypad icon "></i>
                            </a>
                        </div>
                        <?php */ ?>
                        <div class="  text-right">
                            <a href="<?php echo current_url() . '?layout=block' ?>" data-name="layout"
                               data-value="block">
                                <i class="pe-7s-menu icon "></i>
                            </a>
                            <a class="active" href="<?php echo current_url() . '?layout=grid' ?>" data-name="layout"
                               data-value="grid">
                                <i class="pe-7s-keypad icon "></i>
                            </a>
                        </div>
                        <?php //echo $_data_layout(); ?>

                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<?php
$user = user_get_account_info();
if ($user):
    ?>
    <?php
    $input['where']['us.action'] = 'subscribe';
    $input['where']['us.table'] = 'user';
    $input['where']['us.user_id'] = $user->id;
    $input['join'] = array(array('user_storage us', 'us.user_id = user.id'));
    $filter = array();
    $users = mod('user')->get_list($filter, $input);
    //pr_db($users);
    ?>
    <?php if ($users): ?>
    <div>
        <h5 style="border-bottom:1px solid #ccc; padding:10px 5px  ">
            Đang theo dõi
        </h5>

        <div class="slimscroll">

            <?php widget('user')->display_list($users, 'sidebar_follow') ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#slider_point_hander").slider();
        $("#slider_point_hander").on("slide", function (slideEvt) {
            $("#slider_point_value").text(slideEvt.value);
        });
    })
</script>
