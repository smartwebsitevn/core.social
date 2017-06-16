<div class="payment_list list-group mt20">
    <div class="row">
        <?php foreach ($payments as $payment):
            $key = strtolower($payment['payment']->key);
            $use_view = $payment['payment']->paymentUseView();
            $img = public_url('img/payment/' . $key . '.png')
            ?>
            <div class="list-group-item col-md-3 col-sm-12">
                <?php if (!$use_view): ?>
                        <img class="left mr10" src="<?php echo $img; ?>">
                        <?php if (!$use_view): ?>
                            <b class="red"><?php echo $payment['format_amount']; ?></b><br>
                        <?php endif; ?>
                        <label class="tcb-inline">
                            <input class="tc" name="payment_id" value="<?php echo $payment['payment_id'] ?>"
                                   type="radio">
                            <span class="labels"><?php echo $payment['payment']->name; ?></span>

                        </label>
                    <?php else: ?>
                        <a data-toggle="collapse" href="#collapsepayment_<?php echo $key ?>"
                           class="pull-right btn btn-default">
                            <?php echo lang('button_choice'); ?>
                        </a>
                    <?php endif; ?>

                <?php if ($use_view): ?>
                    <div id="collapsepayment_<?php echo $key ?>" class="collapse">
                        <?php view('tpl::_PayGate/' . $payment['payment']->key . '/payment_view', compact('payment')); ?>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>
            </div>

        <?php endforeach ?>
    </div>

    <div name="payment_id_error" class="error  alert alert-danger mt20" style="display: none;"></div>

</div>

<div class="clearfix"></div>
