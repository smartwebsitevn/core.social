<?php
$setting = isset($setting) ? (array)$setting : null;

?>
<div class="row">
    <label class="col-sm-3 "> </label>
    <div class="col-sm-9">
        <div class="error help-block" name="buyout_error"></div>
    </div>
</div>

<div class="form-group param_static">
    <label class="col-sm-3 control-label">
        <?php echo lang('buyout_apply') ?>
    </label>

    <div class="col-sm-9">
        <input type="radio" name="buyout_apply" value="1" class="mt5 " <?php echo ($setting['product_id'] || !$setting)?'checked':'' ?> > <?php echo lang('buyout_apply_product') ?> &nbsp;&nbsp;&nbsp;&nbsp;

        <input type="radio" name="buyout_apply" value="2" class="mt5" <?php echo $setting['lesson_id']?'checked':'' ?> >  <?php echo lang('buyout_apply_lesson') ?>
    </div>

</div>

<div class="buyout_apply_product">
    <?php echo macro('mr::form')->row(  array(
        'param' => 'product_id','name'=>lang('apply_for_product'),'type'=>'select',
        'value'=>$setting['product_id'],'values_row'=>array($products,'id','name'),
        'req'=>1,

    )); ?>
</div>
<div class="buyout_apply_lesson" style="display: none">
   <?php echo macro('mr::form')->row(  array(
       'param' => 'lesson_id','name'=>lang('apply_for_lesson'),'type'=>'select',
       'value'=>$setting['lesson_id'],'values_row'=>array($lessons,'id','name'),
       'req'=>1,
   )); ?>
</div>
<?php view('tpl::voucher/js'); ?>

<?php
/*
echo '<div id="buyout_content" >';
echo '</div>'
*/
?>
<?php /* ?>

<script type="text/javascript">
    (function($)
    {
        $(document).ready(function()
        {
            var main = $('#form');
            // Toggle maintenance
            toggle_status_content('buyout', 'buyout_content');

            function toggle_status_content(param, content)
            {
                toggle_status_content_handle(param, content);

                main.find('input[name='+param+']').change(function()
                {
                    toggle_status_content_handle(param, content);
                });
            }

            function toggle_status_content_handle(param, content)
            {
                var status = (main.find('input[name='+param+']:checked').val() == '1') ? true : false;
                var content = main.find('#'+content);

                if (status)
                {
                    content.slideDown(function(){ $(this).show(); });
                }
                else
                {
                    content.slideUp(function(){ $(this).hide(); });
                }
            }

        });
    })(jQuery);
</script>

<?php */ ?>
<script type="text/javascript">
    $(document).ready(function () {

        show_buyout_apply();
        $('input[name=buyout_apply]').bind('click', function () {
            show_buyout_apply()
        });
        function show_buyout_apply() {
            var val = $('input[name=buyout_apply]:checked').val();

            if (val == '1') {
                $('.buyout_apply_product').show();
                $('.buyout_apply_lesson').hide();

            }
            else {
                $('.buyout_apply_product').hide();
                $('.buyout_apply_lesson').show();
            }
        }

    })
</script>