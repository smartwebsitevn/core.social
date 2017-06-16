<?php
if (!$invoice_order_view) {
    $invoice_order_view = macro('mr::box')->box([
        'title' => lang('title_invoice_order_view'),
        'content' => macro('tpl::invoice_order/macros')->view($invoice_order),
    ]);
}

echo $invoice_order_view;
?>