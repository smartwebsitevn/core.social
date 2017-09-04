<form id="form_filter_advance" class="ajax_form_filter" name="form_filter_advance" event-hook="moduleCoreFilter"
      action="<?php echo $action; ?>"
      method="get">
    <div class="panel">
        <div class="panel-body">
            <?php t('view')->load('tpl::_widget/user/filter/_common/search') ?>

            <div class="block block-filter ">
                <div class="block-content clearfix">
                    <?php t('view')->load('tpl::_widget/user/filter/_common/total') ?>
                    <div class="row">
                        <?php //echo macro()->filter_dropdown_country(['value' => $filter['country_id'], 'values' => $countrys, 'param' => 'country_id', 'name' => lang('filter_country')]); ?>
                        <?php // echo macro()->filter_dropdown_city(['value' => $filter['working_city'], 'values' => $citys, 'param' => 'working_city', 'name' => lang('filter_city')]); ?>
                        <?php echo macro()->filter_dropdown_obj(['value' => $filter['user_group_id'], 'values' =>$user_groups, 'param' => 'user_group_id', 'name' => "Thành viên"]); ?>
                        <?php echo macro()->filter_dropdown_obj(['value' => $filter['job'], 'values' => $cat_type_user_job, 'param' => 'job', 'name' => 'Lĩnh vực']); ?>

                        <?php t('view')->load('tpl::_widget/user/filter/_common/city') ?>
                        <?php t('view')->load('tpl::_widget/user/filter/_common/sort') ?>
                        <?php t('view')->load('tpl::_widget/product/filter/_common/slider_point') ?>
                        <?php t('view')->load('tpl::_widget/product/filter/_common/reset') ?>


                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php //t('view')->load('tpl::_widget/product/filter/_common/follow') ?>

</form>