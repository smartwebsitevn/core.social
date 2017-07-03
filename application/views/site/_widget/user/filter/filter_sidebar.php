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
        <?php foreach ($list as $v=>$label):
            $active_status = (isset($filter[$name]) &&  $filter[$name] == $v) ? 1 : 0;
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
$_data_single = function ($key,$value,$name) use ($filter) {
    ob_start();
    $active_status = (isset($filter[$key]) && $filter[$key])== $value? 1 : 0;
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
?>
<form id="form_filter_advance" name="form_filter_advance" action="<?php echo $action; ?>" method="get">
    <div class="mobile text-right">
        <a class="close-filter"><i class="fa fa-window-close" aria-hidden="true"></i></a>
    </div>
    <section>
        <div class="k-listing-category">
            <h3>Tìm theo danh mục</h3>
            <ul class="k-category-list pd0">
                <?php echo model('user_cat')->get_tree(); ?>
            </ul>
        </div>
        <!--end k-category-->
    </section>
    <div class="widget widget-filter">
        <h3>Tìm theo đặc điểm khóa học</h3>
        <?php echo  $_data_array(["0"=>lang("price_free"),"1"=>lang("price_purchase"),"2"=>lang("price_vip")],"price_option") ?>
        <?php echo  $_data_single("discount","1",lang("price_discount")) ?>
        <?php echo  $_data_single("has_voucher","1",lang("has_voucher")) ?>
        <?php echo  $_data_single("has_combo","1",lang("has_combo")) ?>
    </div>
    <div class="widget widget-filter">
        <h3>Tìm theo thời lượng</h3>
        <?php echo $_data_cat_type($cat_type_time_user, 'time_user_id'); ?>
    </div>
    <div class="widget widget-filter">
        <h3>Tìm theo độ tuổi</h3>
        <?php echo $_data_cat_type($cat_type_age, 'age_id'); ?>

    </div>
    <div class="widget widget-filter">
        <h3>Tìm theo trình độ yêu cầu</h3>
        <?php echo $_data_cat_type($cat_type_level, 'level_id'); ?>
    </div>
    <div class="widget widget-tag">
        <h3>Chủ đề đang hot</h3>
        <?php foreach ($tags as $row): ?>
            <?php if (!$row->seo_url) continue; ?>
            <a href="<?php echo site_url('user_list/tag/' . $row->seo_url) ?>"><?php echo $row->name ?></a>
        <?php endforeach; ?>
    </div>
</form>