<span class="item-price"> <?php    echo $info->_price; ?>  </span>
<?php if ($info->_tax_class): ?>
    <span class="more" data-toggle="tooltip"
          title="<?php echo $info->_tax_class->description; ?>">(<?php echo $info->_tax_class->name ?>)</span>
<?php endif; ?>
<?php
if (isset($info->_price_discount)) : ?>
    Giảm giá khi mua nhiều:
    <div class="info-prod">
        <span class="title">&nbsp;</span>
            <span class="info">
            <?php
            foreach ($info->_price_discount as $k => $v)
                echo "> $k tin bài: {$v[1]}<br />";
            ?>
            </span>
    </div>
<?php endif; ?>

<?php
if (!empty($info->_price_reduce)): ?>
    <div class="info-prod info-price prod-save-money">
     <span class="info"><?php echo $info->_price_reduce ?>
            (<?php echo $info->_price_percent ?>%)</span>
    </div>
<?php endif; ?>
