<a href="<?php echo site_url('cart') ?>" class="dropdown-toggle" data-toggle="dropdown" role="button"
   aria-haspopup="true"
   aria-expanded="false">
    <i class="fa fa-shopping-cart fa-2x"></i><span id="cart-total"><?php echo $count ?></span>
</a>
<?php if (isset($products) && $products): ?>
<ul class="cart-list dropdown-menu">
    <li class="active user">
        <a href="javascript:void(0)">
            Giỏ hàng của bạn
        </a>
    </li>
    <?php
    foreach ($products as $row): ?>
        <li class="row cart-item">
            <div class="col cart-item1 product">
                <img src="<?php echo $row->thumb ?>" alt="">
            </div>
            <div class="col cart-item2 product"><a href="javascript:;"><?php echo $row->name ?></a></div>
            <div class="col cart-item3 product"><?php echo $row->price ?></div>
            <div class="col cart-item4 product">x <?php echo $row->qty ?></div>
            <div class="col cart-item5 product">
                <a class="cart-item-remove" href="<?php echo $row->rowid ?>"><i class="fa fa-trash-o"></i></a>
            </div>
        </li>
    <?php endforeach; ?>
    <li class="row cart-total">
        Tổng tiền: <b>0 đ</b>
    </li>
    <li>
        <a class="row cart-checkout" href="/checkout.html" id="head-btn-checkout">Đặt hàng</a>
    </li>
</ul>
<?php endif; ?>
