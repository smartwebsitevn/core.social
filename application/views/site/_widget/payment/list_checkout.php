<div class="payment_list list-group">
    <?php foreach ($payments as $payment):
        $key = strtolower($payment['payment']->key);
        $use_view = $payment['payment']->paymentUseView();
        // if(in_array($key,['baokimpro','pay123'])) continue;
        ?>
        <div class="list-group-item">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <?php $img = public_url('img/payment/' . $key . '.png') ?>
                    <img class="left mr10" src="<?php echo $img; ?>">
                    <?php if (!$use_view): ?>
                        <b class="red"><?php echo $payment['format_amount']; ?></b><br>
                    <?php endif; ?>
                    <!--<b><?php /*echo $payment['payment']->name; */
                    ?></b><br>-->
                    <small><?php echo $payment['payment']->desc; ?></small>
                </div>
                <div class="col-md-3 col-sm-12">
                    <?php if (!$use_view): ?>
                        <a href="<?php echo $payment['url_pay']; ?>" class="pull-right btn btn-default">
                            <?php echo lang('button_payment'); ?>
                        </a>
                    <?php else: ?>
                        <a data-toggle="collapse" href="#collapsepayment_<?php echo $key ?>"
                           class="pull-right btn btn-default">
                            <?php echo lang('button_choice'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($use_view): ?>
                <div id="collapsepayment_<?php echo $key ?>" class="collapse">
                    <?php view('tpl::_PayGate/' . $payment['payment']->key . '/payment_view', compact('payment')); ?>
                </div>
<div class="clearfix"></div>
            <?php endif; ?>
        </div>

    <?php endforeach ?>

</div>

<div class="clearfix"></div>
