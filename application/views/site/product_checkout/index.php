<form method="post" action="<?php echo site_url("product_checkout") ?>" id="checkout-form" class="form_action">
    <div class="row">
        <div class="col-md-7 padding-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i
                            class="fa fa-map-marker"></i>&nbsp;Thông tin thanh toán<?php //echo lang('billing_information') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php t('view')->load('tpl::product_checkout/_common/contact1') ?>
                    <hr>
                    <?php t('view')->load('tpl::product_checkout/_common/contact2') ?>
                    <hr>
                    <?php t('view')->load('tpl::product_checkout/_common/gtgt') ?>
                </div>
                <div class="panel-footer">
                    <input type="submit" class="btn btn-default pull-right" value="Tiếp tục<?php //echo lang('send_order') ?>"/>

                    <div class="clearfix"></div>
                </div>
            </div>

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
</form>