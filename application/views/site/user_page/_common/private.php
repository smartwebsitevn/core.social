<div class="background"></div>
<div class="container">
    <div class="detail-user">
        <div class="block-content clearfix">
            <div class="item-user <?php echo isset($info->_ads) ? 'item-user-ads' : '' ?> ">
                <div class="clearfix">
                    <?php t('view')->load('tpl::user_page/_common/info') ?>
                </div>
                <div class="clearfix item-action">
                    <hr>
                    <a  href="<?php echo $info->_url_my_account?>" class="btn btn-default"><i class="pe-7s-like"></i> Cập nhập thông tin</a>
                </div>
                <div class="clearfix item-desc">
                    <hr>
                    <?php echo macro()->more_block($info->desc,110); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="nav-links">
        <a href="<?php echo $info->_url_my_page.'?page=posts'//site_url('user_page/posts') ?>" class="btn <?php echo $page=='posts'?'btn-default':'btn-outline'?>">Đã đăng</a>
        <a href="<?php echo $info->_url_my_page.'?page=posts_save'//site_url('user_page/follow') ?>" class="btn <?php echo $page=='posts_save'?'btn-default':'btn-outline'?>">Đã lưu</a>
        <a href="<?php echo $info->_url_my_page.'?page=posts_draft'//site_url('user_page/follow_by') ?>" class="btn <?php echo $page=='posts_draft'?'btn-default':'btn-outline'?>">Bản nháp</a>
    </div>
</div>
