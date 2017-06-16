<div id="cart-total" class="pull-right">
    <table>
        <tbody>
        <?php /* ?>
        <tr>
            <td ><b><?php echo lang('sub_total') ?>: </b></td>
            <td class="pull-right"> <?php echo currency_format_amount($sub_total) ?>  </td>
        </tr>
         <?php */ ?>

        <?php if ($tax_total > 0): ?>
            <tr>
                <td ><b><?php echo lang('tax_total');//Sản phẩm đã bao gồm VAT ?>: </b></td>
                <td class="pull-right"><?php echo currency_format_amount($tax_total); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php
        $discount_total_order = 0;
        if (isset($product_setting->active_discount_total_order) && $product_setting->active_discount_total_order):
            $discount_total_order = ($sub_total * $product_setting['discount_total_order_per']) / 100;
            ?>

            <tr>
                <td class="title">
                    <b><?php echo 'Giảm giá ' . $product_setting['discount_total_order_per'] . '%' ?>
                        :</b>
                </td>
                <td class="total"><?php echo currency_format_amount($discount_total_order) ?></td>
            </tr>
        <?php endif; ?>

        <?php
        $coupon_total_order = 0;
        if (isset($coupon) && $coupon):
            if ($coupon['discount_type'])
                $coupon_total_order = ($sub_total * $coupon['discount']) / 100;
            else
                $coupon_total_order = $coupon['discount'];
            ?>

            <tr>
                <td class="title"><b><?php echo 'Phiếu giảm giá (' . $coupon['code'] . ')' ?>: </b></td>
                <td class="total"><?php echo currency_format_amount($coupon_total_order) ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><b><?php echo lang('total_total') ?>: </b></td>
            <td class="pull-right"><b class="f18" style="color:#f60 "><?php echo currency_format_amount($total_order - $discount_total_order - $coupon_total_order) ?></b></td>
        </tr>
        <!--<tr>
               <td class="title"></td>
                <td align="left">
                  <?php
        //  $link_print = site_url('cart?print=true');
        //  echo popupLink($link_print, '<b style="color:green">(In gio hàng)</b>', 1000, 600) ?>
                </td>
               </tr>-->
        </tbody>
    </table>
</div>

<?php if ($cart_mode == 'allow_edit'): // neu dang xem o che do gio ham?>
    <?php if ($count > 0): // neu co san pham thi hien day du cac nut?>
        <div class="buttons">
            <a class="btn btn-danger btn-sm product-empty-cart "
               data_url="<?php echo site_url('product_cart/destroy') ?>"><?php echo 'Xóa giỏ hàng' ?></a>
            <?php
            $close_popup = 'data-dismiss="modal" ';
           // if ($is_popup)  $close_popup = " onClick='$.colorbox.close(); return false;' "
            ?>
            <a class="btn btn-info btn-sm" <?php echo $close_popup ?>
               href="<?php echo site_url() ?>"><?php echo lang('continue') ?></a>


            <a class="btn btn-warning btn-sm"
               href="<?php echo site_url("product_checkout") ?>"> <?php echo lang('checkout') ?></a>
        </div>
    <?php else: ?>
        <div class="buttons">
            <a class="btn btn-info" href="<?php echo site_url() ?>"><?php echo lang('continue') ?></a>
        </div>
    <?php endif; ?>
<?php endif; ?>
<div class="cleafix"></div>
<div class="text-center">
<img class=" mt20" src="<?php echo public_url('site/theme/images/icon/protect.png') ?>" />
</div>
