
<?php
// Luu vao cache
t('load')->driver('cache');
// Lay danh sach trong cache
$banks = t('cache')->file->get('vnpay_banks_list');
if ($banks) { ?>
<div class="clear"></div>
<h4 class="title">Thanh toán qua ngân hàng</h4>
        <ul class="payment_list" id="vnpay_payment_list">
            <?php foreach ($banks as $bank_id => $bank_name) : ?>
                <li>
                    <a href="<?php echo $payment['url_pay'] . '&bank_id=' . $bank_id; ?>" title="<?php echo $bank_name ?>">
                        
                        <div class="amount">
                            <?php echo $payment['format_amount']; ?>
                        </div>
                        <div class="desc">
                            <?php echo $bank_name ?>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    <div class="clearfix"></div>
<?php } ?>

