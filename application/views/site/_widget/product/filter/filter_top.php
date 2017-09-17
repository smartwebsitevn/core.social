<?php
//pr($filter);
$_data_sort = function () use ($filter, $total_rows, $sort_orders, $sort_order)
{
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
                <span
                    class="ajax-content-product-total "><?php echo isset($total_rows) ? number_format($total_rows) : '-' ?></span>
        Kết quả
    </div>
    <div>
        <div class="block-sorter">
            <?php
            echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data, 'values_opts'=>['value_required'=>true],'param' => 'order', 'name' => lang('ordering_' . $sort_orders[0]), 'class' => 'sort-dropdown']); ?>
        </div>
        <?php /* ?>
        <div class="block-layout act-filter-choice-group">
            <a class="act-filter-choice active" href="Javascript:;" data-name="layout" data-value="block">
                <i class="pe-7s-menu icon "></i>
            </a>
            <a class="act-filter-choice" href="Javascript:;" data-name="layout" data-value="grid">
                <i class="pe-7s-keypad icon "></i>
            </a>
        </div>

        <div class="  text-right">
            <a class="active" href="<?php echo current_url().'?layout=block' ?>" data-name="layout" data-value="block">
                <i class="pe-7s-menu icon "></i>
            </a>
            <a class="" href="<?php echo current_url().'?layout=grid' ?>" data-name="layout" data-value="grid">
                <i class="pe-7s-keypad icon "></i>
            </a>
        </div>
  <?php */ ?>
        <div class="block-layout">
            <input name="point" class="act-filter-slider" id="slider_point_hander" type="hidden" data-provide="slider"
                   data-slider-min="0"
                   data-slider-max="100"
                   data-slider-step="10"
                   data-slider-value="0" data-slider-tooltip="hide"/>

            <div class="clearfix"></div>
            <span id="slider_point"><span id="slider_point_value">0</span> points</span>

        </div>
        <?php /* ?>
    <div class="block-layout">
            <a class="search-results  act-filter-dropdown " href="Javascript:;" data-name="order" data-value="id|desc">
                <i class="fa fa-list"></i>
            </a>
            <a class="search-results  act-filter-dropdown " href="Javascript:;" data-name="order" data-value="id|desc">
                <i class="fa fa-list"></i>
            </a>
        </div>
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
/*$_data_layout = function () use ($filter, $_data_dropdown) {
    ob_start();
    */ ?><!--
    <div class="sorter pull-right">

    </div>
    --><?php /*return ob_get_clean();
};*/

?>
<form id="form_filter" data-group="product-filter"  event-hook="productFilter"
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
            <div class="action">
                <button class="btn btn-outline" type="submit">Tìm bài
                    viết<?php //echo lang("search_recruit") ?></button>
            </div>
        </div>
    </div>
    <?php //echo macro()->navbar_collapse_start(); sticky-element ?>
    <div class="block block-filter ">
        <div class="block-content clearfix">
            <?php
            //pr($product_cats);
            ?>
            <div class="row">
                <div class="block-content-left col-md-8 col-sm-8 col-xs-12">
                    <?php
                    //$product_cats = model('product_cat')->get_list_hierarchy([], ['show' => 1]);
                    // echo macro()->filter_dropdown_category(['value' => $filter['cat_id'], 'values' => $product_cats, 'param' => 'price', 'name' => lang('filter_category'), 'obj' => 'product_cat']);
                    $product_cats = model('type_cat')->get_list_hierarchy([], ['show' => 1]);

                    echo macro()->filter_dropdown_category(['value' => $filter['type_cat_id'], 'values' => $product_cats, 'param' => 'type_cat_id', 'name' => lang('filter_category'), 'obj' => 'type_cat', 'attr' => ['data-ajax-filter' => true]]); ?>
                    <?php //echo macro()->filter_dropdown_obj(['value' => $filter['price'], 'values' => $range_type_price, 'param' => 'price', 'name' => lang('filter_price')]); ?>
                    <?php //echo macro()->filter_dropdown_obj(['value' => $filter['manufacture_id'], 'values' => $manufactures, 'param' => 'manufacture_id', 'name' => lang('filter_manufacture')]); ?>
                    <?php //echo macro()->filter_dropdown_country(['value' => $filter['country_id'], 'values' => $countrys, 'param' => 'country_id', 'name' => lang('filter_country')]); ?>
                    <div class="ajax-filter"></div>
                    <div class="action-filter">
                        <a href="#0" class="btn-clear-all"  style="display: none"><!--<i class="pe-7s-close " style="font-size: 32px"></i>-->Xóa Bộ Lọc</a>
                        <!--<a href="#0" class="btn btn-link btn-clear-all"><i class="pe-7s-lock"    style="font-size: 32px"></i></a>-->
                    </div>
                </div>
                <div class="block-content-right  col-md-4 col-sm-4 col-xs-12">
                    <div class="row">
                        <?php //echo $_data_layout(); ?>
                        <?php echo $_data_sort(); ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php
    $user_current = user_get_account_info();
    ?>
    <?php if ($user_current):
        $user_current = mod('user')->add_info($user_current);
        // pr($user_current);
        ?>
        <div class="block block-post ">
            <div class="block-content clearfix">
                <div class="">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <a href="<?php echo $user_current->_url_view; ?>" class="item-img">
                            <img src="<?php echo $user_current->avatar->url_thumb ?>"
                                 alt="<?php echo $user_current->name; ?>">
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <p class="message">Hôm nay bạn muốn chia sẻ điều gì?</p>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <a href="<?php echo site_url('product_post') ?>" class="btn btn-default pull-right">Đăng tin</a>
                    </div>

                </div>
            </div>
        </div>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#slider_point_hander").slider();
        $("#slider_point_hander").on("slide", function (slideEvt) {
            $("#slider_point_value").text(slideEvt.value);
        });
    })
</script>