<div class="item-price">
    <?php if (isset($row->is_alway_in_stock) && !$row->is_alway_in_stock): ?>
        <?php if ($row->quantity <= 0): ?>
            <label class="label label-danger pull-left">
                Hết hàng
            </label>

        <?php endif; ?>
    <?php elseif (isset($row->_price_old)): ?>


        <span class="old-price  pull-left">
            <?php echo $row->_price_old ?>
        </span>
    <?php endif; ?>
    <span class="price pull-right"><?php echo $row->_price ?></span>
</div>

