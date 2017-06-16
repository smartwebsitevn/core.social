<?php
$invoice = $invoice_order->invoice;
$obj = mod('product')->get_info($invoice_order->product_id);

$_tran_payment = function ($invoice_order) {
    $tran = $invoice_order->invoice->tran;
    ob_start(); ?>
    <?php if ($tran && $tran->payment)
        echo $tran->payment->name;
    else
        //echo  "Chuyển khoản";
        echo "--";
    ?>
    <p>
        <?php echo t('html')->img(public_url('img/world/' . strtolower($invoice_order->user_country_code) . '.gif')); ?>
        <?php echo $invoice_order->user_ip; ?>
    </p>

    <?php return ob_get_clean();
};
$info = macro()->info([
    lang('id') => $invoice_order->id,
    lang('type') => $invoice_order->service_name,
    lang('desc') => t('html')->a($obj->_url_view, $invoice_order->title, ['target' => '_blank']) . '<br>' .
        implode('<br>', (array)$invoice_order->order_desc),
    lang('tran_status') => macro()->status_color($invoice->tran_status, lang('tran_status_' . $invoice->tran_status)),

    lang('order_status') => macro()->status_color($invoice_order->order_status, $invoice_order->order_status_name),

    // lang('quan')       	=> $invoice_order->qty,
    lang('amount') => $invoice_order->{'format:amount'},
    // lang('fee_tax')    	=> $invoice_order->{'format:fee_tax'},
    lang('payment') => $_tran_payment($invoice_order),
    lang('created') => $invoice_order->{'format:created,full'},
    '' => '<a href="' . site_url('invoice_order') . '" class="btn btn-default">Trở lại</a>',
]);

echo macro('mr::box')->box([
    'title' => lang('title_invoice_order_view'),
    'content' => $info
]);

