<div class="background"></div>
<div class="container">
    <div class="detail-user">
        <div class="block-content clearfix">
            <div class="item-user <?php echo isset($info->_ads) ? 'item-user-ads' : '' ?> ">
                <div class="clearfix">
                    <div class="item-photo">
                        <a href="<?php echo $info->_url_view; ?>" class="item-img">
                            <img src="<?php echo $info->avatar->url_thumb ?>"
                                 alt="<?php echo $info->name; ?>">
                        </a>

                        <div class="links">
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-angle-right-circle"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-mail"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-call"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-id"></i>

                            </a>
                        </div>

                    </div>
                    <div class="item-info">
                        <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $info)); ?>
                        <div class="item-name"><a href="<?php echo $info->_url_view; ?>">
                                <?php echo $info->name; ?></a>
                            <?php echo widget('user')->action_favorite($info) ?>
                        </div>
                        <div class="item-profession"><?php echo character_limiter($info->profession, 250); ?>    </div>
                        <div class="item-meta">
                            <?php if (isset($info->_city)): ?>
                                <span class="place"> <i
                                        class="pe-7s-map-marker"></i> <b><?php echo $info->_city->name ?></b></span>
                            <?php endif; ?>
                            <span
                                class="posts"> <b><?php echo number_format($info->post_total) ?></b> <?php echo lang("count_post") ?></span>

                            <span
                                class="points"> <b><?php echo number_format($info->vote_total) ?></b> <?php echo lang("count_point") ?></span>
                            <span
                                class="follows"> <b><?php echo number_format($info->follow_total) ?></b> <?php echo lang("count_follow") ?></span>


                        </div>


                    </div>
                    <div class="item-profile">
                        <div class="avatar">
                            <a href="<?php echo $info->_url_view ?>">
                                <img
                                    src="<?php echo $info->avatar->url_thumb ?>" alt="<?php echo $info->name; ?>"> </a>
                        </div>
                        <div class="group">  <?php echo $info->user_group_name; ?></div>
                    </div>
                </div>
                <div class="clearfix item-action">
                    <hr>
                    <?php // widget('user')->action_follow($info,$user) ?>
                    <?php //widget('user')->action_favorite($info,$user) ?>
                    <?php //widget('user')->action_share($info) ?>
                    <?php //widget('user')->action_close() ?>
                    <a class="btn btn-default"><i class="pe-7s-like"></i> Theo dõi</a>
                    <a class="btn btn-outline"><i class="pe-7s-comment"></i> Nhắn tin</a>
                </div>
                <div class="clearfix item-desc">
                    <hr>
                    <?php echo character_limiter($info->desc, 250); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="nav-links">
        <a href="<?php echo $info->_url_view.'?page=posts'//site_url('user_page/posts') ?>" class="btn <?php echo $page=='posts'?'btn-default':'btn-outline'?>">Posts</a>
        <a href="<?php echo $info->_url_view.'?page=follow'//site_url('user_page/follow') ?>" class="btn <?php echo $page=='follow'?'btn-default':'btn-outline'?>">Theo dõi ai</a>
        <a href="<?php echo $info->_url_view.'?page=follow_me'//site_url('user_page/follow_by') ?>" class="btn <?php echo $page=='follow_me'?'btn-default':'btn-outline'?>">Ai theo dõi</a>
    </div>
</div>
