<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/' . $this->_template . '/';
$_data_banks = function ($payment) use($amount) {
    ob_start();
    $filter = [];
    $filter['show'] = true;
    $banks = mod('bank')->get_list($filter);
    ?>
    <style>
        .form-banking {
            padding: 10px;
            background: #fff;
            margin-top: 20px;
        }

        .form-banking h2 {
            text-transform: uppercase;
            font-size: 17px;
        }

        .transfer_tut {
            margin: 10px 0;
            line-height: 1.5em;
        }

        .tut_content {
            padding: 0 20px;
        }

        .form-banking ul {
            margin: 0;
            padding: 0;
        }

        .form-banking ul li {
            background: none;
            border: 0 none;
            line-height: 16px;
            margin: 5px 0;
            padding: 0 0 0 10px;
            display: block;
            float: left;
            width: 100%
        }

        .form-banking ul li span {
            float: left;
            width: 50%;
        }
    </style>

    <div class="form-banking">

        <div class="transfer_tut">
            <div class="tut_content">
                <?php /* ?>
                <div class="mt20">
                    <b><?php echo lang('bank_hint_tranfer_to_content') ?>:</b>
                    <div>
                        <input type="text" value="Thanh toan don hang <?php // echo $invoice->_id ?>"
                               onclick="this.select();"
                               style="width: 100%; color:#f60;font-weight:bold;height:35px;border:1px solid #ececec;margin-top: 10px"
                               class="textC">
                    </div>

                </div>
                <hr>
                <?php */ ?>

                <div>
                    <b><?php echo lang('bank_hint_tranfer_to_amount') ?>:</b>
                    <font class="fontB f13 green"><?php echo currency_format_amount($amount) ?></font>
                    <font class="fontB f13"
                          style="color:red"> <?php echo '(' . lang('note') . ': ' . lang('deliver_pay_fee') . ')' ?></font>
                </div>
                <hr>
                <div>
                    <b><?php echo lang('bank_support') ?>:</b>
                </div>
                <hr>
                <?php foreach ($banks as $bank): //pr($bank); ?>
                    <div class="well mt10">
                        <ul>
                            <li>
                                <span><?php echo lang('bank_hint_tranfer_to_bank') ?>:</span>
                                <font class="fontB f13 green"><?php echo $bank->name ?></font>
                            </li>
                            <li>
                                <span><?php echo lang('bank_hint_tranfer_to_bank_branch') ?>:</span>
                                <font class="fontB f13 green"><?php echo $bank->branch ?></font>
                            </li>
                            <li>
                                <span><?php echo lang('bank_hint_tranfer_to_acc_num') ?>:</span>
                                <font class="fontB f13 green"><?php echo $bank->acc_id ?></font>

                            </li>
                            <li>
                                <span><?php echo lang('bank_hint_tranfer_to_acc_name') ?>:</span>
                                <font class="fontB f13 green"><?php echo $bank->acc_name ?></font>
                            </li>
                            <?php if ($bank->url): ?>
                                <li class="textC">
                                    <a class="button button-border medium green f"
                                       href="<?php echo $bank->url ?>"><?php echo lang('transfer_to_bank') ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <?php return ob_get_clean();
};
?>
<?php /* switch ($payment) {
    case 'payment':

        echo widget('payment')->list_choice($amount);
        //echo widget('payment')->list_checkout_invoice($invoice->id);
        break;
    case 'banking':
        ?>
        <?php
        echo $_data_banks($payment);
        break;
    case
    'shipping_home':
        break;
        ?>

    <?php } */ ?>

<div class="panel-heading mt30">
    <h3 class="panel-title"><i class="fa fa-credit-card"></i> <?php echo lang('payment_method') ?></h3>
</div>
<div class="panel-body" id="confirm-payment">
    <p>Những hương thức thanh toán được hỗ trợ:</p>

    <?php
    foreach (model('payment_method')->filter_get_list(['show'=>1]) as $payment) {
        $active =false;
        if($payment->is_default)
            $active =true;

        ?>
        <hr>
        <div class="payment-gate">
            <div class="radio">
                <label>
                        <input type="radio" class="toggle_content tc" name="payment" value="<?php echo $payment->id ?>" <?php echo $active ? 'checked="checked"' : '' ?> >
                        <span ><?php echo $payment->name ?></span>
                    </label>
            </div>

            <div id="payment_content_<?php echo  $payment->id?>" class="payment_content" style="display: none">
                <?php echo html_entity_decode($payment->description); ?>
                <?php
               // if($payment->id==1)
                   // echo widget('payment')->list_choice($amount);
                //echo widget('payment')->list_checkout_invoice($invoice->id);
                ?>
            </div>
        </div>

        <div class="clearfix"></div>
        <?php
    }
    ?>
    <div name="payment_error" class="error  alert alert-danger mt20" style="display: none;"></div>

    <div class="box-hidden" style="display: none;">
        <?php echo module_get_setting('product', 'product_banking') ?>
    </div>
</div>
