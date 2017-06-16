<?php if ($can_do): ?>
    <?php if ($product->price_is_auction): ?>
        <a class="btn btn-default btn-block btn-rounded" _submit="true" href="javascript:void(0)"
           title='<?php echo lang("action_auction_product") ?>'
            ><i class="fa fa-opencart "></i> <?php echo lang("action_auction_product") ?></a>

    <?php elseif ($product_order_quick): ?>
        <a class="btn btn-default btn-block btn-rounded" _submit="true" href="javascript:void(0)"
           title='<?php echo lang("action_order_product") ?>'
            ><i class="fa fa-opencart "></i> <?php echo lang("action_order_product") ?></a>
    <?php else: ?>
    <a class="btn btn-default btn-block btn-rounded" _submit="true" href="javascript:void(0)"
       title='<?php echo lang("action_add_cart") ?>'
        ><i class="fa fa-opencart "></i> <?php echo lang("action_add_cart") ?></a>
<?php endif; ?>


<?php else: ?>
<a class="btn btn-default btn-block btn-rounded" href="javascript:void(0)"
   title='Hết hàng<?php // echo lang("action_add_cart") ?>'
    ><i class="fa fa-opencart "></i>  Hết hàng<?php //echo lang("action_add_cart") ?></a>
<?php endif; ?>
