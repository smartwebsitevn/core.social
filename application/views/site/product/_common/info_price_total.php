<?php if (isset($info->_price)): ?>
<div id="price-total-wraper"  class="form-group">
    <label for="input-voucher" class="col-sm-6 control-label">Giá tổng:</label>
    <div class="col-sm-6">
        <span id="price-total" class="item-price "><?php echo $info->_price; ?> </span>
    </div>
</div>
<?php endif; ?>