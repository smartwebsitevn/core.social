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
                <span class="ajax-content-total"><?php echo isset($total_rows) ? number_format($total_rows) : '-' ?></span>    Kết quả
    </div>
    <div >
        <div class="block-sorter">
           <?php      echo macro()->filter_dropdown_list(['value' => $sort_order, 'values' => $sort_orders_data,'values_opts'=>['value_required'=>true], 'param' => 'order', 'name' =>  lang('ordering_' . $sort_orders[0]), 'class' => 'sort-dropdown']); ?>
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

<form  id="form_filter"  event-hook="moduleCoreFilter" action="<?php echo $action; ?>"
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
                <div class="block-content-left col-md-8 col-sm-8 col-xs-12">

                    <?php //echo macro()->filter_dropdown_country(['value' => $filter['country_id'], 'values' => $countrys, 'param' => 'country_id', 'name' => lang('filter_country')]); ?>
                    <?php // echo macro()->filter_dropdown_city(['value' => $filter['working_city'], 'values' => $citys, 'param' => 'working_city', 'name' => lang('filter_city')]); ?>
                    <?php echo macro()->filter_dropdown_obj(['value' => $filter['user_group_id'], 'values' =>$user_groups, 'param' => 'user_group_id', 'name' => "Thành viên"]); ?>
                    <?php echo macro()->filter_dropdown_obj(['value' => $filter['job'], 'values' => $cat_type_user_job, 'param' => 'job', 'name' => 'Lĩnh vực']); ?>
                    <!-- city-->
                    <div class="dropdown search-dropdown">
                        <div class="dropdown-toggle"  type="button" data-toggle="dropdown" >
                            <span class="search-rendered">Địa điểm</span>
                            <span class="search-caret"></span>
                        </div>
                        <span class="search-remove"></span>
                        <div class="dropdown-menu dropdown-2colums clearfix" >
                            <div class="dropdown-menu dropdown-menu-left ">
                                <div class="form-group">
                                    <input type="text"  class="searachSelect form-control lg">
                                </div>
                                <div class="slimscroll">
                                    <?php foreach($citys as $row){ ?>
                                        <div class="search-results checkbox <?php echo (isset($filter['working_city'])&& is_array($filter['working_city']) && in_array($row->id, $filter['working_city'])) ? 'active_filter' : ''?>">
                                            <label>
                                                <input type="checkbox" name="working_city[]" value="<?php echo $row->id ?>"> <span><?php echo $row->name ?></span>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php  /* ?>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="form-group">
                                    <input type="text" placeholder="<?php echo lang("city_out_the_country")?>" class="form-control lg searachSelect">
                                </div>
                                <div class="slimscroll limit-height">
                                    <ul>
                                        <?php $path = public_url().'/img/world/'; ?>
                                        <?php foreach ($countrys as $v): ?>
                                            <?php ?>
                                    <li class="search-results active act-filter <?php echo (isset($filter['groupcountries']) && $row->id == $filter['groupcountries']) ? 'active_filter' : ''?>" data-name="groupcountries" data-value="<?php echo $group->id; ?>">
                                        <a class="search-results-option" href="#"><?php echo $group->name; ?></a>
                                    </li>
                                     <?php  ?>

                                            <?php //foreach ($group->countries as $v): ?>
                                            <li class="search-results act-filter <?php echo (isset($filter['country']) && $row->id == $filter['country']) ? 'active_filter' : ''?>" data-name="country" data-value="<?php echo $v->id; ?>">
                                                <a class="search-results-option" href="#"  data-value="<?php echo $v->id; ?>">
                                                    <img src="<?php echo $path.strtolower($v->id).'.gif'?>"> <?php echo $v->name; ?></a>
                                            </li>
                                            <?php //endforeach; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php  */ ?>

                        </div>
                    </div>
                    <a href="#0" class="btn-clear-all "  style="display: none"><!--<i class="pe-7s-close " style="font-size: 32px"></i>-->Xóa Bộ Lọc</a>

                </div>
                <div class="block-content-right  col-md-4 col-sm-8 col-xs-12">
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
