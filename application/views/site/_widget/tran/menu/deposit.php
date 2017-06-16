<nav class="navbar-menu">
    <div class="navbar-header">
        <button data-target="#navbar-collapse-tran-bonus" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div id="navbar-collapse-tran-deposit"  class="collapse navbar-collapse">
        <ul class="nav nav-tabs mb20">
            <li class="<?php echo ($current == 'deposit_card') ? 'active' : '' ?>"><a  href="<?php echo site_url('deposit_card')?>">Thẻ điện thoại</a></li>
            <li class="<?php echo ($current == 'voucher') ? 'active' : '' ?>"><a  href="<?php echo site_url('voucher')?>">Mã Voucher</a></li>
            <li class="<?php echo ($current == 'deposit') ? 'active' : '' ?>"><a  href="<?php echo site_url('deposit')?>">Cổng thanh toán</a></li>
            <li class="<?php echo ($current == 'deposit_bank') ? 'active' : '' ?>"><a  href="<?php echo site_url('deposit_bank')?>">Chuyển khoản</a></li>
        </ul>
    </div>
</nav>

