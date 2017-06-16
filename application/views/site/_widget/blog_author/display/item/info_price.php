<div class="item-price">
    <span class="price"><?php echo $info->_price ?></span>
    <?php if (isset($info->_price_old)): ?>
        <span class="old-price">
							<?php echo $info->_price_old ?>
							</span>
    <?php endif; ?>

</div>