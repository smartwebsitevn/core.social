<style>
    .payment_list{

    }
    .payment_list li {
        float: left;
        border: 1px solid #D5C6C6;
       /* padding: 7px;*/
        margin: 0 4px 20px 9px;
        min-width: 150px;
        max-width: 150px;
        line-height: 20px;
        background: #fff;
        text-align: center;
        list-style: none;
    }


    .payment_list li:hover {
        border-color: #AAAAAA #AAAAAA #666666;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.7), 0 0 3px #FFFFFF inset;
    }



    .payment_list li img {
        padding: 0;
       /* width: 100%;*/
        margin: 5px auto;
    }

    .payment_list li .amount {
       color: red;
        background:#ededec;
        padding:7px
    }
    .payment_list li .desc {
        padding:7px;
        height: 50px;
        overflow: hidden;
    }
</style>
<?php
$list =[];
foreach ($payments as $payment){
    $use_view = $payment['payment']->paymentUseView();
if($use_view ) continue;
    $list[]=$payment;
}


if(count($list)): ?>
<h4 class="title">Thanh toán bằng cổng thanh toán trực tuyến</h4>
<ul class="payment_list">
    <?php foreach ($list as $payment):
        $key = strtolower($payment['payment']->key);
        $img = public_url('img/payment/' . $key . '.png')
        ?>
        <li>

            <a href="<?php echo $payment['url_pay']; ?>">
                <img  src="<?php echo $img; ?>">

                <div class="amount">
                    <?php echo $payment['format_amount']; ?>
                </div>
                <div class="desc">
                    <?php echo $payment['payment']->desc ?>
                </div>
            </a>

            <div class="clear"></div>

        </li>

    <?php endforeach ?>

</ul>
<?php endif; ?>
<!-- baokimpro -->
<?php
foreach ($payments as $payment)
{
    $use_view = $payment['payment']->paymentUseView();
    if ($use_view)
    {
        view('tpl::_PayGate/'.$payment['payment']->key.'/payment_view', compact('payment'));
        break;
    }
}
?>

<div class="clearfix"></div>
