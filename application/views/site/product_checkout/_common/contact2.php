<div  id="checkout-shipping-info">
    <div class="checkbox">
        <label>

            <input class="toggle_content tc" type="checkbox" data-type="single" id="shipping_to_other_address"
                   name="shipping_to_other_address" value="1"><span>Người nhận hàng khác người thanh toán</span>
        </label>
    </div>
    <div id="shipping_to_other_address_content" style="display: none" >

        <?php
        //pr($checkout);
        $form = array(
            array(
                'param' => 'shipping_name',
                'placeholder' => lang('name'),
                'type' => 'text',
                'value' => $checkout['shipping_name'],
                'req' => true
            ),
            array(
                'param' => 'shipping_phone',
                'placeholder' => lang('phone'),
                'type' => 'text',
                'value' => $checkout['shipping_phone'],
                'req' => true
            ),
            array(
                'param' => 'shipping_email',
                'placeholder' => lang('email'),
                'type' => 'text',
                'value' => $checkout['shipping_email'],
            ),
            array(
                'param' => 'shipping_city',
                'type' => 'select2',
                'value' => $checkout['shipping_city'],
                'values_row' => array($cities, 'id', 'name')
            ),
            array(
                'param' => 'shipping_address',
                'placeholder' => lang('address'),
                'type' => 'textarea',
                'value' => $checkout['shipping_address'],
            ),
            array(
                'param' => 'shipping_note',
                'placeholder' => lang('note'),
                'type' => 'textarea',
                'value' => $checkout['shipping_address'],
            ),

        );

        foreach ($form as $row) {
            echo macro('mr::advForm')->row($row);
        }

        ?>

    </div>
</div>