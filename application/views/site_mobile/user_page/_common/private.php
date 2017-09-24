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
    <?php widget('site')->upload($upload_banner, array('temp' => 'tpl::_widget/user/upload/banner')) ?>

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
            <a href="<?php echo $info->_url_my_page . '?page=posts'//site_url('user_page/posts') ?>"
               class="btn btn-link <?php echo $page == 'posts' ? 'active' : '' ?>">
                <span class="text">Đã đăng</span><br>
                <span class="value"><?php echo number_format($info->post_is_publish) ;// ?></span>

            </a>
            <a href="<?php echo $info->_url_my_page . '?page=posts_save'//site_url('user_page/follow') ?>"
               class="btn btn-link <?php echo $page == 'posts_save' ? 'active' : '' ?>">
                <?php $saved =model('product_to_favorite')->filter_get_total(['user_id'=>$info->id]);
                ?>

                <span class="text">Đã lưu</span><br>
                <span class="value"><?php echo number_format($saved) ?></span>
            </a>
            <?php /* ?>
                            <a href="<?php echo $info->_url_my_page.'?page=posts_draft' ?>" class="btn btn-link <?php echo $page=='posts_draft'?'active':''?>">

                                <span class="text">Bản nháp</span><br>
                                <span class="value">0<?php //echo number_format($info->post_total) ?></span>
                            </a>
                             <?php */ ?>

            <a href="<?php echo $info->_url_my_page . '?page=follow'//site_url('user_page/follow') ?>"
               class="btn btn-link <?php echo $page == 'follow' ? 'active' : '' ?>">
                <span class="text">Đang theo dõi</span><br>
                <span class="value"><?php echo number_format($info->follow_total) ?></span>


            </a>
            <a href="<?php echo $info->_url_my_page . '?page=follow_by' ?>"
               class="btn btn-link <?php echo $page == 'follow_by' ? 'active' : '' ?>">
                <span class="text">Người theo dõi</span><br>
                <span class="value"><?php echo number_format($info->follow_by_total) ?></span>
            </a>
            <?php   /* ?>
            <a href="<?php echo $info->_url_my_page . '?page=notice' ?>"
               class="btn btn-link <?php echo $page == 'notice' ? 'active' : '' ?>">
                <span class="text">Thông báo</span><br>
                <span class="value">-<?php //echo number_format($info->follow_by_total) ?></span>
            </a>

            <a href="<?php echo $info->_url_my_page . '?page=message' ?>"
               class="btn btn-link <?php echo $page == 'notice' ? 'active' : '' ?>">
                <span class="text">Tin nhắn</span><br>
                <span class="value">-<?php //echo number_format($info->follow_by_total) ?></span>
            </a>
               <?php   */ ?>

        </div>
        <div class="item-action">
            <a href="<?php echo $info->_url_my_account ?>" class="btn btn-default btn-round btn-xs">Chỉnh sửa hồ
                sơ</a>
        </div>
    </div>
</div>
<?php t('view')->load('tpl::user_page/_common/_js') ?>
