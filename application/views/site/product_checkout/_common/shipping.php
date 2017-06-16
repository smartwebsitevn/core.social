<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-truck"></i> Thông tin giao hàng<?php //echo lang('shipping_method') ?></h3>
</div>
<div class="panel-body">

    <div class="alert ">
        <p class="fontB f18"><?php  echo $checkout["name"] ?></p>

        <p><?php echo $checkout["city_name"] ?></p>
        <p><?php echo $checkout["address"] ?></p>

        <p><?php echo $checkout["phone"] ?></p>
        <?php if ($checkout["email"]): ?>
            <p><?php echo $checkout["email"] ?></p>
        <?php endif; ?>
        <?php if ($checkout["shipping_to_other_address"]): ?>
            <hr>
            <p class="fontB f18"><?php  echo $checkout["shipping_name"] ?></p>
            <p><?php echo $checkout["shipping_city_name"] ?></p>
            <p><?php echo $checkout["shipping_address"] ?></p>
            <p><?php echo $checkout["shipping_phone"] ?></p>
            <?php if ($checkout["shipping_email"]): ?>
                <p><?php echo $checkout["shipping_email"] ?></p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($checkout["get_gtgt"]): ?>
            <hr>
            <p class="fontB f18"><?php  echo $checkout["company_name"] ?></p>
            <p><?php echo $checkout["company_tax_code"] ?></p>
            <p><?php echo $checkout["company_address"] ?></p>
        <?php endif; ?>
    </div>

    <hr>

    <div id="checkout-shipping-gate">
        <?php //* ?>
        <p>Những gói chuyển phát được hỗ trợ:</p>
        <?php foreach ($shipping_methods as $row):
            $active =false;
           /* if($row->is_default)
                $active =true;*/
            ?>

            <div class="shipping-gate">
                <div class="radio">
                    <label>
                        <input data-cost="<?php echo lang($row->cost) ?>"  type="radio" class="toggle_content tc" name="shipping" value="<?php echo $row->id ?>" <?php echo $active ? 'checked="checked"' : '' ?> >
                        <span ><?php echo lang($row->name) ?> ( + <?php echo currency_format_amount($row->cost) ?> )</span>
                    </label>
                </div>

                <div id="shipping_content_<?php echo  $row->id?>" class="payment_content" style="display: none">
                    <?php //echo html_entity_decode($row->description); ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div name="shipping_error" class="error  alert alert-danger mt20" style="display: none;"></div>
        <?php //*/ ?>
    </div>
</div>