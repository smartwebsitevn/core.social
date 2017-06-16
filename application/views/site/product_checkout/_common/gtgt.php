<div  id="checkout-gtgt">
    <div class="checkbox">
        <label>

            <input class="toggle_content tc" type="checkbox" data-type="single" id="get_gtgt"
                   name="get_gtgt" value="1"><span>Lấy hóa đơn GTGT</span>
        </label>
    </div>

    <div id="get_gtgt_content" style="display: none" >


        <?php
        //pr($checkout);
        $form = array(
            array(
                'param' => 'company_name',
                'placeholder' => lang('company_name'),
                'type' => 'text',
                'value' => $checkout['company_name'],
                'req' => true
            ),
            array(
                'param' => 'company_tax_code',
                'placeholder' => lang('company_tax_code'),
                'type' => 'text',
                'value' => $checkout['company_tax_code'],
                'req' => true
            ),
            array(
                'param' => 'company_address',
                'placeholder' => lang('company_address'),
                'type' => 'textarea',
                'value' => $checkout['company_address'],
                'req' => true

            ),

        );

        foreach ($form as $row) {
            echo macro('mr::advForm')->row($row);
        }

        ?>

    </div>
</div>