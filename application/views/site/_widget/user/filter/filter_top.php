<?php
//pr($filter);
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

    <div class="block-total">
                <span class="ajax-content-user-total fontB"><?php echo isset($total_rows) ? number_format($total_rows) : '-' ?></span>    Kết quả
    </div>
    <div >
        <div class="block-sorter">
            <?php
            echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data, 'param' => 'order', 'name' => 'Mới nhất', 'class' => 'sort-dropdown']); ?>
        </div>
        <div class="block-layout">
            <a class="search-results  act-filter-dropdown " href="Javascript:;" data-name="order" data-value="id|desc">
                <i class="pe-7s-menu icon " ></i>
            </a>
            <a class="search-results  act-filter-dropdown " href="Javascript:;" data-name="order" data-value="id|desc">
                <i class="pe-7s-keypad icon " ></i>
            </a>
        </div>

        <?php /* ?>
            <div class="col-xs-12 col-sm-6">
                    Hiển thị sp/ 1 trang
                    <select style="width:130px" name="limit" class="form-control select_show">
                        <option value="20">20 tin bài</option>
                        <option value="35">35 tin bài</option>
                        <option value="45">45 tin bài</option>
                        <option value="50">50 tin bài</option>
                    </select>
            </div>
             <?php */ ?>
    </div>
    <?php return ob_get_clean();
};
$_data_layout = function () use ($filter) {
    ob_start();
    ?>

    <?php return ob_get_clean();
};

?>

<form id="form_filter_advance" name="form_filter_advance" event-hook="moduleCoreFilter" action="<?php echo $action; ?>"
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
                                <input type="text" class="select-input-field" placeholder="Tìm thành viên"
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
            <div class="action">
                <button class="btn btn-outline" type="submit">Tìm thành
                    viên<?php //echo lang("search_recruit") ?></button>
            </div>
        </div>
    </div>
    <?php //echo macro()->navbar_collapse_start(); sticky-element ?>
    <div class="block block-filter ">
        <div class="block-content clearfix">
            <?php
            //pr($user_cats);
            ?>
            <div class="row">
                <div class="block-content-left col-md-9 col-sm-10 col-xs-12">

                    <?php echo macro()->filter_dropdown_country(['value' => $filter['country_id'], 'values' => $countrys, 'param' => 'country_id', 'name' => lang('filter_country')]); ?>
                    <a href="#0" class="btn btn-link btn-clear-all">Xóa dữ liệu
                        lọc<?php //echo lang("clear_all_filters")  ?></a>
                </div>
                <div class="block-content-right  col-md-3 col-sm-3 col-xs-12">
                <div class="row">
                    <?php //echo $_data_layout(); ?>
                    <?php echo $_data_sort(); ?>
                </div>
                </div>

            </div>
        </div>
    </div>
    <?php //echo macro()->navbar_collapse_end(); ?>
</form>
