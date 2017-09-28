<form class="ajax_form_filter"  event-hook="moduleCoreFilter"
      action="<?php echo $action; ?>"
      method="get">
    <div class="panel">
        <div class="panel-body">
            <?php t('view')->load('tpl::_widget/user/filter/_common/search') ?>
            <div class="block block-filter ">
                <div class="block-content clearfix">
                        <?php t('view')->load('tpl::_widget/user/filter/_common/total') ?>
                        <?php t('view')->load('tpl::_widget/user/filter/_common/sort') ?>
                        <?php t('view')->load('tpl::_widget/product/filter/_common/slider_point') ?>
                        <?php t('view')->load('tpl::_widget/product/filter/_common/reset') ?>
                    <?php echo  macro()->modal_start(['id'=>'modal-user-filter','name'=>'Lọc kết quả']); ?>
                    <div class="product-total">
                        Có <span  class="ajax-content-total "><?php echo isset($total_rows) ? number_format($total_rows) : '...' ?></span> kết quả
                    </div>
                    <?php //echo macro()->filter_dropdown_country(['value' => $filter['country_id'], 'values' => $countrys, 'param' => 'country_id', 'name' => lang('filter_country')]); ?>
                    <?php // echo macro()->filter_dropdown_city(['value' => $filter['working_city'], 'values' => $citys, 'param' => 'working_city', 'name' => lang('filter_city')]); ?>
                    <?php echo macro()->filter_dropdown_obj(['value' => $filter['user_group_id'], 'values' =>$user_groups, 'param' => 'user_group_id', 'name' => "Thành viên"]); ?>
                    <?php echo macro()->filter_dropdown_obj(['value' => $filter['job'], 'values' => $cat_type_user_job, 'param' => 'job', 'name' => 'Lĩnh vực']); ?>

                    <?php t('view')->load('tpl::_widget/user/filter/_common/city') ?>

                    <div class="clearfix"></div>
                    <a href="#0" class="btn btn-default mr10 " data-dismiss="modal" aria-label="Close"> Xem kết quả</a>
                    <a href="#0" class="btn btn-outline  btn-clear-all" style="display: none">Xóa bộ lọc</a>
                </div>
                <?php echo macro()->modal_end(); ?>
                </div>
            </div>

        </div>
    </div>



</form>