<?php
$banner_background = '';
//$banner_class = '';
if ($info->banner) {
    $banner_background = ' class="active" style="background-image: url(' . $info->banner->url . ')" ';
    //$banner_class = ' active';
}
?>
<div id="banner_background" <?php echo $banner_background ?> >
    <div class="background1"></div>
    <div class="background2"></div>
</div>
<div class="container">
    <div class="detail-user">
            <div class="item-user <?php echo isset($info->_ads) ? 'item-user-ads' : '' ?> ">
                <?php t('view')->load('tpl::user_page/_common/info-photo') ?>
                <div class="item-info">
                    <?php t('view')->load('tpl::user_page/_common/info-meta') ?>
                </div>
            </div>
        <div class="nav-links">

            <a href="<?php echo $info->_url_view . '?page=posts'//site_url('user_page/posts') ?>"
               class="btn btn-link <?php echo $page == 'posts' ? 'active' : '' ?>">
                <span class="text">Đã đăng</span><br>
                <?php //$post_total =model('product')->filter_get_total(['user_id'=>$info->id,'show'=>1]) ?>
                <span class="value"><?php echo number_format($info->post_is_publish) ;// ?></span>

            </a>

            <a href="<?php echo $info->_url_view . '?page=follow'//site_url('user_page/follow') ?>"
               class="btn btn-link <?php echo $page == 'follow' ? 'active' : '' ?>">
                <span class="text">Đang theo dõi</span><br>
                <span class="value"><?php echo number_format($info->follow_total) ?></span>


            </a>
            <a href="<?php echo $info->_url_view . '?page=follow_by'//site_url('user_page/follow_by') ?>"
               class="btn btn-link <?php echo $page == 'follow_by' ? 'active' : '' ?>">
                <span class="text">Người theo dõi</span><br>
                <span class="value"><?php echo number_format($info->follow_by_total) ?></span>
            </a>
            <a href="<?php echo $info->_url_view . '?page=info'//site_url('user_page/follow_by') ?>"
               class="btn btn-link <?php echo $page == 'info' ? 'active' : '' ?>">
                <span class="text">Giới thiệu</span><br>
                <span class="value"><i class="pe-7s-id"></i></span>
            </a>
        </div>
        <div class="item-action">
            <?php widget('user')->action_subscribe($info) ?>
        </div>

    </div>
</div>
