<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo lang('title_topup_offline'); ?></h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <td class="col-25"><?php echo lang('topup_account'); ?></td>
            <td class="col-25"><?php echo lang('topup_amount'); ?></td>
            <td class="col-20"><?php echo lang('topup_provider'); ?></td>
            <td class="col-20"><?php echo lang('topup_type'); ?></td>
            </thead>
            <tbody>
            <?php $order_options = $topup_offline->invoice_order->order_options ?>
            <?php foreach ($order_options as $row): ?>
                <?php $row = (array)$row ?>
                <?php if (!isset($row['account'])) return; ?>

                <tr>
                    <td>
                        <?php echo $row['account']; ?>
                    </td>

                    <td>
                        <?php echo currency_format_amount($row['amount_total']); ?>
                    </td>

                    <td>
                        <?php echo $row['provider']; ?>
                    </td>

                    <td>
                        <?php echo lang('topup_type_' . $row['type']); ?>
                    </td>

                </tr>
            <?php endforeach; ?>

            <style>
                .list2 li span {
                    display: inline-block;
                    width: 150px
                }
            </style>
            <tr>
                <td colspan="10">
                    <ul class="list2 order_amounts">
                        <li>
                            <span><?php echo lang('amount_total'); ?>:</span>
                            <font class="blue"><?php echo $topup_offline->_amount_total ?></font>
                        </li>

                        <!--<li>
                            <span><?php /*echo lang('amount_discount'); */?>:</span>
                            <font class="blue"><?php /*echo $topup_offline->_amount_discount */?></font>
                        </li>-->

                        <li>
                            <span><?php echo lang('amount_payment'); ?>:</span>
                            <font class="red"><?php echo $topup_offline->_amount ?></font>
                        </li>


                        <li class="status">
                            <span><?php echo lang('status'); ?>:</span>
                            <font class="<?php echo mod('order')->status_name($topup_offline->status) ?>">
                                <?php echo macro()->status_color(mod('order')->status_name($topup_offline->status), lang('order_status_' . mod('order')->status_name($topup_offline->status))) ?>
                            </font>
                        </li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>