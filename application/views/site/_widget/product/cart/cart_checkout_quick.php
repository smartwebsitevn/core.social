<form method="post" action="<?php echo site_url("product_checkout") ?>" id="checkout-form" class="form_action">
    <div class="form-group param_text ">
        <div class="text-center">
            <?php $row = $list[0] ?>
            <p>
                <b class="f20"> <?php echo $row->name ?></b>
            </p>
            <?php if ($row->option_html): ?>
                <p>
                    <?php echo $row->option_html ?>
                </p>
            <?php endif; ?>
            <p>
                <b class="f18" style="color: #f7941e"><?php echo currency_format_amount($row->total_price) ?></b>
            </p>

        </div>
    </div>
    <?php

    $cities = model('city')->filter_get_list(['show'=>1,'country_id' => 230]);

    $checkout = null;
    $form = array(
        array(
            'name' => '',
            'param' => 'name',
            'placeholder' => lang('name'),
            'type' => 'text',
            'value' => $checkout['name'],
            'req' => true
        ),
        array(
            'name' => '',
            'param' => 'phone',
            'placeholder' => lang('phone'),
            'type' => 'text',
            'value' => $checkout['phone'],
            'req' => true
        ),
        array(
            'name' => '',

            'param' => 'email',
            'placeholder' => lang('email'),
            'type' => 'text',
            'value' => $checkout['email'],
        ),
         array(
             'name' => '',
             'param' => 'city',
             'type' => 'select',
             'value' => $checkout['city'],
             'values_opts' => array('default_name'=>'Chọn tỉnh thành'),
             'values_row' => array($cities, 'id', 'name'),
             'req' => true

         ),

    );
    foreach ($form as $row) {
        if (isset($row['refer']))
            echo macro('mr::advForm')->row($row, $form);
        else
            echo macro('mr::advForm')->row($row);
    }

    ?>
    <div class="text-center">
        <!--  <input type="submit" class="btn btn-default" value="Gửi đơn hàng"/>-->
        <a _submit="true" class="btn btn btn-default">Gửi đơn hàng</a>
        <a class="btn btn-info btn-outline " data-dismiss="modal"> Cancel</a>
    </div>
    <div class="clearfix"></div>

</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('.form_action').nstUI('formActionAdv'  );
    })
</script>