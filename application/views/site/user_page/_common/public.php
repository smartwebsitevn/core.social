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
                    <?php //widget('user')->action_share($info) ?>
                    <?php widget('user')->action_subscribe($info) ?>
                    <?php widget('user')->action_message($info) ?>
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
        <a href="<?php echo $info->_url_view.'?page=posts'//site_url('user_page/posts') ?>" class="btn <?php echo $page=='posts'?'btn-default':'btn-outline'?>">Posts</a>
        <a href="<?php echo $info->_url_view.'?page=follow'//site_url('user_page/follow') ?>" class="btn <?php echo $page=='follow'?'btn-default':'btn-outline'?>">Theo dõi ai</a>
        <a href="<?php echo $info->_url_view.'?page=follow_by'//site_url('user_page/follow_by') ?>" class="btn <?php echo $page=='follow_by'?'btn-default':'btn-outline'?>">Ai theo dõi</a>
    </div>
</div>
