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
                <?php echo model('lesson_cat')->get_tree(); ?>
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
        <?php echo $_data_cat_type($cat_type_time_aboutus, 'time_aboutus_id'); ?>
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
            <a href="<?php echo site_url('aboutus_list/tag/' . $row->seo_url) ?>"><?php echo $row->name ?></a>
        <?php endforeach; ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        /*=========== Filter ============*/
        $("#form_filter_advance .filter-tick").on("click", function () {
            $(this).toggleClass('active');
            if ($(this).hasClass('active')) {
                html = '<input type="hidden" name="' + $(this).data('name') + '" value="' + $(this).data('value') + '"  />';
                $(this).append(html);
            }
            else {
                $(this).find('input').remove();
            }
            aboutusFilter();
        });
        $("#form_filter_advance input,#form_filter_base input").on('keydown', function (e) {
            if (e.keyCode == '13') {
                aboutusFilter();
            }
        });
        $("#form_filter_advance input,#form_filter_base input").on('change ', function () {
            aboutusFilter();
        });
        $("#form_filter_advance select,#form_filter_base select").on('change', function () {
            aboutusFilter();
        });

        /*=========== Pagging Ajax ===============*/
        $("#product-category .pagging-ajax a").bind("click", function () {
            var page = $(this).attr("href");
            if (page != undefined) {
                page = page.replace('/', '');
                if (page == '')   page = 0;
                //alert(page);
                aboutusFilter(page);
            }
            return false;
        });

    });
    function aboutusFilter(page) {
        //alert(page);
        var f = document.form_filter_advance;
        // f.limitstart.value = page;
        // alert(page);
       // nfc.loader("show");
        var $target = $("#c_list_result");
        $target.append('<span class="loader_block"></span>');
        var form_filter_base = $('#form_filter_base').formToArray(false, []);
        //f.push({name: "_submit", value: "true", type: 'hidden'});


        $('#form_filter_advance').ajaxSubmit({
            data: form_filter_base,
            dataType: 'json',
            success: function (rs, statusText, xhr, $form) {
                history.pushState('data', '', $('#form_filter_advance').attr('action') + '?' + $('#form_filter_advance').serialize() + "&" + $('#form_filter_base').serialize());
                //nfc.loader("hide");
                 $target.find('span.loader_block').remove()
                if (rs.status) {
                    $target.html(rs.content);
                    // var go_to = $target.offset().top - 150;
                    // $('html, body').animate({scrollTop: go_to}, 500);
                }
            },

        });
    }
</script>