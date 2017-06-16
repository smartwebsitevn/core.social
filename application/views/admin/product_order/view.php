<div class="portlet">
    <div class="portlet-heading bg-primary">
        <div class="portlet-title">
            <h4><i class="fa fa-info"></i> Chi tiết đơn hàng <?php //echo lang('title_invoice_view'); ?></h4>
        </div>

    </div>
    <?php //pr($invoice); ?>
    <div class="portlet-body ">
        <?php
        $invoice->invoice_order->invoice->_payment_name = $invoice->_payment_name;
        echo macro('tpl::invoice_order/macros')->view($invoice->invoice_order);
        ?>
        <table class="table table-bordered table-striped table-hover tc-table">
            <tbody>
            <tr>
                <td class="row_label" style="width:25%"><b><?php echo lang('shipping'); ?></b></td>
                <td class="row_item">
                    <span class="label label-success"><?php echo $invoice->_shipping_name; ?></span>

                </td>
            </tr>
            <tr>
                <td class="row_label"><b><?php echo lang('payment'); ?></b></td>
                <td class="row_item">
                    <span class="label label-success"><?php echo $invoice->_payment_name; ?></span>
                </td>
            </tr>
            <tr>
                <td class="row_label"><b>Thông tin khách hàng</b></td>
                <td class="row_item">
                    <?php
                    //pr($invoice->info_contact);
                    if ($invoice->info_shipping) {
                        ?>
                        <address>
                            <strong class="red">Thông tin nhận hàng</strong><br>
                            <strong><?php echo lang('name') ?>:</strong> <?php echo $invoice->info_shipping->name ?><br>
                            <strong><?php echo lang('phone') ?>:</strong> <?php echo $invoice->info_shipping->phone ?> <br>
                            <strong><?php echo lang('email') ?>:</strong> <?php echo $invoice->info_shipping->email ?>
                            <br>
                            <strong><?php echo lang('address') ?>
                                :</strong> <?php echo $invoice->info_shipping->address ?>
                            <?php echo ', ' . $invoice->info_shipping->city_name ?>
                            <?php //echo  ', ' . $invoice->info_shipping->country_name ?>

                        </address>
                        <?php
                    }
                    ?>
                    <address>
                        <strong class="red">Thông tin thanh toán</strong><br>
                        <strong><?php echo lang('name') ?>:</strong> <?php echo $invoice->info_contact->name ?><br>
                        <strong><?php echo lang('phone') ?>:</strong> <?php echo $invoice->info_contact->phone ?><br>
                        <?php if(isset($invoice->info_contact->email)): ?>
                        <strong><?php echo lang('email') ?>:</strong> <?php echo $invoice->info_contact->email ?><br>
                        <?php endif; ?>
                        <strong><?php echo lang('address') ?>:</strong> <?php echo $invoice->info_contact->address ?>
                        <?php echo ', ' . $invoice->info_contact->city_name ?>
                        <?php //echo  ', ' . $invoice->info_contact->country_name ?>
                    </address>
                    <?php if (isset($invoice->info_contact->auction_price) ): ?>
                        <address>
                            <strong class="red">Thông tin đấu giá</strong><br>
                            <strong>Giá đặt<?php //echo lang('price') ?>
                                :</strong> <?php echo $invoice->info_contact->auction_price ?><br>
                            <strong>Thông tin<?php //echo lang('tax_code') ?>
                                :</strong> <?php echo $invoice->info_contact->auction_intro ?>
                            <br>

                        </address>
                    <?php endif; ?>
                    <?php if (isset($invoice->info_contact->get_gtgt) && $invoice->info_contact->get_gtgt): ?>
                        <address>
                            <strong class="red">Khách lấy hóa đơn GTGT</strong><br>
                            <strong><?php echo lang('name') ?>
                                :</strong> <?php echo $invoice->info_contact->company_name ?><br>
                            <strong>Mã số thuế<?php //echo lang('tax_code') ?>
                                :</strong> <?php echo $invoice->info_contact->company_tax_code ?>
                            <br>
                            <strong><?php echo lang('address') ?>
                                :</strong> <?php echo $invoice->info_contact->company_address ?>

                        </address>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table class="table table-bordered table-striped table-hover tc-table">
                        <tr>
                            <td width="1%">STT</td>
                            <td>Tên</td>
                            <td width="10%">Số lượng</td>
                            <td width="20%">Thành tiền</td>
                            <td width="15%">Thuế</td>
                            <td width="15%">Trạng thái</td>
                        </tr>
                        <?php
                        $count = 1;
                        // pr($products);

                        foreach ($invoice->_orders as $order) {
                            $obj = objectExtract(['id' => $order->product_id], $products, true);
                            $url = mod('product')->add_info_url($obj)->_url_view;
                            ?>
                            <tr>
                                <td><?php echo $count ?>.</td>
                                <td>
                                    <a target="_blank" href="<?php echo $url ?>"><?php echo $order->title ?></a>
                                    <?php if($order->desc ): ?><br>
                                    <?php echo $order->desc ?>
                                <?php endif; ?>


                                    <?php
                                    $option = json_decode($order->order_options);
                                    $voucher_coupon = '';
                                    if (isset($option->voucher_type) && in_array($option->voucher_type, ['coupon', 'buyout'])):
                                        $voucher_coupon = $option->voucher_discount_type == 1 ? currency_format_amount($option->voucher_discount) : $option->voucher_discount . '%';

                                        ?>
                                        <b>Áp dụng Voucher<?php //echo lang('payment');
                                            ?></b>
                                        <strong
                                            class="f19 red"><?php echo $voucher_coupon; ?></strong>
                                        (<?php echo $option->voucher_key; ?>)
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $order->qty ?></td>
                                <td class="red"><?php echo currency_format_amount_default($order->amount) ?></td>
                                <td class="red"><?php echo currency_format_amount_default($order->fee_tax) ?></td>
                                <td class="text-right option" colspan="10">
                                    <?php
                                    $cando = in_array($order->order_status, [App\Invoice\Library\OrderStatus::PENDING, App\Invoice\Library\OrderStatus::PROCESSING]);;
                                   //pr($order); ?>
                                    <?php if ($cando): ?>
                                        <?php /* ?>
                                        <a href="" _url="<?php echo admin_url("product_order/active/" . $order->id); ?>"
                                           class="btn btn-primary btn-sm verify_action"
                                           notice="Bạn có chắc muốn kích hoạt đơn hàng này?<?php //echo lang('notice_active_order'); ?>"
                                            ><?php echo lang('button_active'); ?></a>
                                     <?php */ ?>
                                        <a href="" _url="<?php echo admin_url("product_order/completed/" . $order->id); ?>"
                                           class="btn btn-primary btn-sm btn-block mb2 verify_action"
                                           notice="Bạn có chắc muốn xác nhận đã giao đơn hàng này?<?php //echo lang('notice_active_order'); ?>"
                                            >Xác nhận giao hàng<?php ///echo lang('button_active'); ?></a>
                                        <a href="" _url="<?php echo admin_url("product_order/cancel/" . $order->id); ?>"
                                           class="btn btn-danger btn-sm btn-block  verify_action"
                                           notice="Bạn có chắc muốn hủy bỏ đơn hàng này?<?php //echo lang('notice_cancel_order', ['action' => lang('button_cancel')]); ?>"
                                            >Hủy đơn hàng<?php //echo lang('button_cancel'); ?></a>
                                        <?php else: ?>
                                        <?php echo  macro()->status_color($order->order_status,lang('order_status_'.$order->order_status)) ?>
                                    <?php endif ?>



                                </td>

                            </tr>
                            <?php
                            $count++;
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="row_label" style="width:25%">Thành tiền<?php //echo lang('total_amount'); ?></td>
                <td class="row_item red">
                    <strong><?php echo $invoice->_amount; ?></strong>
                </td>
            </tr>
            <tr>
                <td class="row_label">Phí vận chuyển<?php //echo lang('fee_shipping'); ?></td>
                <td class="row_item">
                    <?php echo $invoice->_fee_shipping; ?>
                </td>
            </tr>
            <tr>
                <td class="row_label">Thuế<?php //echo lang('fee_tax_total'); ?></td>
                <td class="row_item">
                    <?php echo $invoice->_fee_tax; ?>
                </td>
            </tr>
            <tr>
                <td class="row_label">Tổng tiền<?php //echo lang('grand_total'); ?></td>
                <td class="row_item red">
                    <strong><?php echo $invoice->_total_amount; ?></strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
