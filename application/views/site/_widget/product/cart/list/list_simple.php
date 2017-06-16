<?php if (isset($list) && $list): ?>
    <style type="text/css">
        #cart-total table, #cart-total table tr, #cart-total table td {
            border: none;

        }
    </style>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Sản phẩm<?php //echo lang('name') ?></th>
                <th width="18%"><?php echo lang('quantity') ?></th>
                <th style="text-align: right">Thành tiền<?php //echo lang('total') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            //pr($list);
            if ($count == 0) {
                echo '<tr><td colspan="10">' . lang('empty') . '</td></tr>';
            } else {
                $sub_total = 0;
                $tax_total = 0;
                $total_order = 0;
               // pr($list);
                foreach ($list as $row) {
                    // pr($row);
                    //== Total
                    $sub_total += $row->subtotal;
                    $tax_total += $row->tax_value;
                    $total_order += $row->total_price;
                    ?>
                    <tr>
                        <td class="name">
                            <a href="<?php echo $row->url_view ?>"> <?php echo $row->name ?></a>
                            <?php if ($row->option_html): ?>
                                <?php echo $row->option_html ?>
                            <?php endif; ?>
                        </td>
                        <td class="quantity text-center">
                            <?php if ($cart_mode == 'allow_edit'): // neu dang xem o che do gio ham?>
                                <input type="text" class="text-center input input-mini" size="5"
                                       value="<?php echo $row->qty ?>"
                                       id="product-qty-key-<?php echo $row->rowid ?>">
                                <div class="action_update pull-right">
                                    <a class="product-update-cart"
                                       data_url="<?php echo site_url("product_cart/update") ?>"
                                       data_product_key="<?php echo $row->rowid ?>" title="Update"><i
                                            class="fa fa-refresh"></i></a>&nbsp;&nbsp;
                                    <a class="product-delete-cart"
                                       data_url="<?php echo site_url("product_cart/delete") ?>"
                                       data_product_key="<?php echo $row->rowid ?>" title="Delete"><i
                                            class="fa fa-close"></i></a>
                                </div>
                            <?php else:
                                echo $row->qty ?>
                            <?php endif; ?>
                        </td>
                        <td class="total" style="text-align: right">
                            <b><?php echo currency_format_amount($row->total_price) ?></b></td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>

        <?php /*?>
 <div class="cart-coupon">
 Nhập mã giảm giá của bạn ở đây: <br />
 	<input type="text" id="product-coupon-cart" style="width:90px" class="input input-medium"   value="<?php echo $coupon['code']?>" >
    <a  class="product-apply-coupon-cart btn" data_url="<?php echo site_url("product_cart/applyCoupon")?>"> <?php echo lang('core.apply')?></a>
 </div>
<?php */ ?>
        <?php if ($count > 0): ?>
            <hr/>
            <?php t('view')->load('tpl::_widget/product/cart/item/info_total',['sub_total'=>$sub_total,'tax_total'=>$tax_total,'total_order'=> $total_order]) ?>
        <?php endif; ?>
    </div>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>