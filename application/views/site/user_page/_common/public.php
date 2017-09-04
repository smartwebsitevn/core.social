<?php $banner_background = '';
if ($info->banner) {
    $banner_background = ' style="background-image: url(' . $info->banner->url . ')" ';
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
                <div class="item-profile">
                    <?php t('view')->load('tpl::user_page/_common/info-profile') ?>
                </div>

            </div>
        <div class="nav-links">

            <a href="<?php echo $info->_url_view . '?page=posts'//site_url('user_page/posts') ?>"
               class="btn btn-link <?php echo $page == 'posts' ? 'active' : '' ?>">
                <span class="text">Đã đăng</span><br>
                <span class="value"><?php echo number_format($info->post_total) ?></span>

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
        </div>
        <div class="item-action">
            <?php t('view')->load('tpl::_widget/user/display/item/info_attach_name', ['row' => $info]) ?>

            <?php //widget('user')->action_share($info) ?>
            <?php widget('user')->action_subscribe($info) ?>
            <?php //widget('user')->action_message($info) ?>
            <span class="dropdown">
                                     <a href="#0" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                         <i class="pe-7s-more" aria-hidden="true"></i>
                                     </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a title="Thông tin liên hệ " class=" do_action"
                                               data-url="<?php echo $info->_url_view_profile ?>">Thông
                                                tin liên hệ</a></li>
                                        <li><a title="Nhắn tin" href="<?php echo $info->_url_message ?>">Nhắn tin</a>
                                        </li>
                                    </ul>
                            </span>
        </div>

    </div>
</div>
