<div class="row">
    <div class="col-md-7 padding-5">
        <form method="post" action="<?php echo site_url("product_checkout/confirm") ?>" id="confirm-form"
              class="form_action">
            <input type="hidden" name="geo_zone_id" value="<?php echo htmlentities(json_encode($geo_zone_id)) ?>"/>

            <div class="panel panel-default">
                <?php t('view')->load('tpl::product_checkout/_common/shipping') ?>
                <?php  t('view')->load('tpl::product_checkout/_common/payment') ?>
                <div class="panel-footer mt30">
                    <a href="<?php echo site_url('product_checkout') ?>" class="btn pull-left" ><?php echo lang('back') ?> </a>
                    <input type="submit" class="btn btn-default pull-right" value="<?php echo lang('order_confirm') ?>"/>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-5 padding-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i
                        class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;Thông tin đơn hàng<?php //echo lang('cart_content') ?></h3>
            </div>
            <div class="panel-body" id="checkout-cart">
                <?php    widget("product")->cart(['cart_mode'=>'view'],'cart_checkout');?>
                <hr/>
                <?php /* ?>
                    <div class="panel-body alert alert-success">
                        <?php
                        echo macro('mr::advForm')->row(array(
                            'param' => 'voucher',
                            'name' => lang('voucher'),
                            'type' => 'text',
                            'desc' => lang('import_voucher_discount_code_if_you_have')
                        ));
                        ?>
                    </div>
                     <?php */ ?>

            </div>
        </div>
    </div>
</div>
