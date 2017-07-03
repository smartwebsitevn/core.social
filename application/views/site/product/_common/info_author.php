<?php if (isset($info->_author) && $info->_author): ?>
    <?php
    //pr($info->_author);
    $_data_author = function ($author) {
        // pr($author);
        //$author =user_add_info($author);
        //$author =user_add_info_other($author);
        ob_start() ?>
        <div class="block block-author">
            <div class="block-content">
                <div class="logo-cty">
                    <a href="<?php echo $author->_url_view ?>">
                        <img
                            src="<?php echo $author->avatar->url_thumb ?>">                    </a>

                </div>
                <div class="links">
                    <a data-toggle="modal" data-target="#modal-company-info" >
                        <i class="pe-7s-angle-right-circle"></i>

                    </a>
                    <a data-toggle="modal" data-target="#modal-company-info">
                        <i class="pe-7s-mail"></i>

                    </a>
                    <a data-toggle="modal" data-target="#modal-company-info" >
                        <i class="pe-7s-call"></i>

                    </a>
                    <a data-toggle="modal" data-target="#modal-company-info" >
                        <i class="pe-7s-id"></i>

                    </a>
                </div>
                <div class="name-cty">
                    <a href="<?php echo $author->_url_view ?>"><?php echo $author->name ?></a>
                </div>
                <div class="short-cty">  <?php echo $author->profession ?></div>
                <div class="item-meta">
                    <?php if(isset($author->_city)): ?>
                    <span class="place"> <i class="pe-7s-map-marker"></i> <b><?php  echo $author->_city->name ?></b></span>
                    <?php endif; ?>
                    <span  class="posts"> <b><?php echo number_format($author->post_total) ?></b> <?php echo lang("count_post") ?></span>

                    <span class="points"> <b><?php echo number_format($author->vote_total) ?></b> <?php echo lang("count_point") ?></span>
            <span   class="follows"> <b><?php echo number_format($author->follow_total) ?></b> <?php echo lang("count_follow") ?></span>


                </div>

                <hr>
                <a class="btn btn-default"><i class="pe-7s-like"></i> Theo dõi</a>
                <a class="btn btn-outline"><i class="pe-7s-comment"></i> Nhắn tin</a>
                <hr>

                <div class="item-des">
                    <?php echo macro()->more_block($author->desc); ?>
                </div>

            </div>

        </div>

        <?php return ob_get_clean();
    };
    ?>
    <?php echo $_data_author($info->_author); ?>

<?php endif; ?>