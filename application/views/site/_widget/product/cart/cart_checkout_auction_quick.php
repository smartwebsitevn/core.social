<?php
$cities = model('city')->filter_get_list(['show'=>1]);
$product_id =$list[0]->id;
$product =model('product')->get_info($product_id,'price_is_auction_data');
$auction_data= null;
if($product)
    $auction_data = json_decode($product->price_is_auction_data,1);

$checkout = null;
?>
<form method="post" action="<?php echo site_url("product_checkout") ?>" id="checkout-form" class="form_action">
    <div class="form-group param_text col-sm-12 ">
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
        <?php if(isset($auction_data['intro']) && $auction_data['intro']): ?>
        <?php echo $auction_data['intro'] ?>
        <?php endif; ?>
    </div>
    <?php

    $form = array(
        array(
            'name' => '',
            'param' => 'auction_price',
            'placeholder' => lang('auction_price'),
            'type' => 'text',
            'value' => $checkout['auction_price'],
            'req' => true
        ),
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
             'type' => 'select2',
             'value' => $checkout['city'],
             'values_row' => array($cities, 'id', 'name'),
             'req' => true

         ),
        array(
            'name' => '',
            'param' => 'auction_intro',
            'placeholder' => lang('auction_intro'),
            'type' => 'textarea',
            'value' => $checkout['intro'],
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