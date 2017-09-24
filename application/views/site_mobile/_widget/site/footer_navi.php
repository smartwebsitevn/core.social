<ul id="footer_tool">
    <?php /*if(user_is_login()): ?>
    <li class="navigator-item <?php echo $type == "user_page"?"active":'' ?>">
        <a href="<?php echo site_url('my-page') ?>" >
            <i class="pe-7s-home"></i>
        </a>
    </li>
    <?php endif; */ ?>

    <li class="navigator-item <?php echo $type == "user_list"?"active":'' ?>">
        <a href="<?php echo site_url('thanh-vien') ?>" >
            <i class="pe-7s-users"></i>
        </a>
    </li>

    <li class="navigator-item <?php echo $type == "product_list"?"active":'' ?>">
        <a  href="<?php echo site_url('ban-tin') ?>">
            <i class="pe-7s-news-paper"></i>
        </a>
    </li>
    <?php if(in_array($type,['product_list','product_filter'])): ?>
    <li class="navigator-item">
        <a  data-dismiss="modal" data-toggle="modal" data-target="#modal-product-filter"
            href="#">
            <i class="pe-7s-filter"></i>
        </a>
    </li>
    <?php elseif(in_array($type,['user_list','user_filter'])): ?>
    <li class="navigator-item">
        <a  data-dismiss="modal" data-toggle="modal" data-target="#modal-user-filter"
            href="#">
            <i class="pe-7s-filter"></i>
        </a>
    </li>
    <?php endif; ?>
    <li class="navigator-item">
        <a data-dismiss="modal" data-toggle="modal" data-target="#system_user_notify"
           href="#">
            <i class="pe-7s-bell" style="font-size:23px"></i>
            <span class="count"><?php //echo $total_unread ?></span>
        </a>
    </li>
</ul>
