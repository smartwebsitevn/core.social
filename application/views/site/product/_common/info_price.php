<?php if (isset($info->_price_old)): ?>
<div class="item-price-old">
            <?php echo $info->_price_old ?>
        </div>
<?php endif; ?>
<div class="item-price">
    <span class="item-price-current">
    <?php   echo $info->_price; ?>
          </span>
    <?php if (isset($info->_price_special_reduce)): ?>
        <span class="item-price-special-reduce">
            <?php /* ?>
            <span class="info"><?php echo $info->_price_special_reduce ?>
             <?php */ ?>
         (<?php echo '-'.$info->_price_special_percent ?>%)</span>
        </span>
        <?php if (isset($info->_price_special_begin)): ?>
        <div class="item-price-special-time">
         (<?php echo $info->_price_special_begin.' - '.$info->_price_special_end ?>)</div>
        </span>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php /*if ($info->_tax_class): ?>
    <span class="more" data-toggle="tooltip"
          title="<?php echo $info->_tax_class->description; ?>">(<?php echo $info->_tax_class->name ?>)</span>
<?php endif;*/ ?>

<?php if (isset($info->_price_discount)) : ?><br>
<b> Giảm giá khi mua:</b>
    <div class="item-price-discount">
            <?php
            foreach ($info->_price_discount as $k => $v)
                echo "từ <b>$k</b> sản phẩm: {$v[1]}<br />";
            ?>
    </div>
<?php endif; ?>


<div class="clear mt5"></div>