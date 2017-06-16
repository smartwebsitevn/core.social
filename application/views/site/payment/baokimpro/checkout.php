<?php

// Luu vao cache
t('load')->driver('cache');
// Lay danh sach trong cache
$banks = t('cache')->file->get('baokimpro_banks_list');
//$baokimpro = new App\Payment\PayGate\BaoKimPro\Library;
//	pr($baokimpro->a());

if ($banks) {
    require_once(APPPATH . 'app/Payment/PayGate/BaoKimPro/Constants.php');
    $path_img = public_url('img/payment/baokimpro/images');

    ?>

    <?php
    $bank_type = function ($payment_method_type) use ($payment, $banks, $path_img) {
        //$html .= '<li><img class="img-bank"   id="' . $bank['id'] .  '" src="' .  $bank['logo_url'] . '" title="' .  $bank['name'] . '"/></li>';
        ?>

        <ul class="payment_list">
            <?php foreach ($banks as $bank) : ?>
                <?php if ($bank['payment_method_type'] != $payment_method_type) continue; ?>
                <li>
                    <a href="<?php echo $payment['url_pay'] . '&bank_id=' . $bank['id']; ?>"
                       title="<?php echo $bank['name'] ?>">
                        <img src="<?php echo $path_img . '/bank/' . $bank['id'] . '.jpg'; ?>">

                        <div class="amount">
                            <?php echo $payment['format_amount']; ?>
                        </div>
                        <div class="desc">
                            <?php echo $bank['name'] ?>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php } ?>

    <!--<link rel="stylesheet" href="<?php /*echo public_url('img/payment/baokimpro/css.css'); */?>" type="text/css"/>-->
    <div class="clearfix"></div>

        <h4 class="title">Thanh toán trực tuyến bằng thẻ ATM nội địa</h4>
        <div class="bank_list">
            <?php echo $bank_type(PAYMENT_METHOD_TYPE_LOCAL_CARD); ?>
            <div class="clr"></div>
        </div>
    <?php /* ?>


    <div class="row">
            <h4 class="title">Chuyển khoản InternetBanking</h4>
            <div class="bank_list">
                <?php echo $bank_type(PAYMENT_METHOD_TYPE_INTERNET_BANKING); ?>
            </div>
    </div>
    <div class="row">
            <h3 class="title">Thanh toán trực tuyến bằng thẻ quốc tế <!--<img src="images/safe.png" border="0" style="vertical-align: bottom; margin-left: 5px;" />--></h3>
            <div class="bank_list">
                <?php echo $bank_type(PAYMENT_METHOD_TYPE_CREDIT_CARD); ?>
            </div>
    </div>

    <div class="row-fluid method" id="0">
        <div class="icon"><img src="<?php echo $path_img ?>/sercurity.png" border="0"/></div>
        <div class="info">
            <div class="bk_logo"><a href="http://baokim.vn" target="_blank"><img
                        src="<?php echo $path_img ?>/bk_logo.png" border="0"/></a></div>
            <span class="title">Thanh toán Bảo Kim</span>
            <span class="desc">Thanh toán bằng tài khoản Bảo Kim (Baokim.vn)</span>
        </div>
        <div class="check_box"></div>
    </div>
<?php */ ?>

<?php } ?>