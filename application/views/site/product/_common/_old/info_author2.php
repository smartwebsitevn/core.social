<?php pr($info); if (isset($info->_author) && $info->_author): ?>
    <?php
    //pr($info->_author);
    $_data_author = function ($author) {
        // pr($author);
        ob_start() ?>
        <div class="col-sm-6">
            <div class=" item-gv">
                <div class="item-photo">
                    <a href="<?php echo $author->_url_view ?>">
                        <img class="instructor__instructor-image" src="<?php echo $author->avatar->url ?>">
                    </a>
                </div>
                <div class="item-detail">
                    <h4 class="item-name"> <?php echo $author->name ?></h4>
                    <span> <?php echo $author->profession;//$author->information     ?></span>

                    <div class="social">
                        <?php  /*if ($author->email): ?>
                            <a href="<?php echo $author->email ?>" class="sc-link sc-email"><i class="fa fa-emails"
                                                                    aria-hidden="true"></i></a>
                        <?php endif;*/ ?>
                        <?php if ($author->googleplus): ?>
                            <a href="<?php echo $author->googleplus ?>" class="sc-link sc-google"><i class="fa fa-google-plus"
                                                                                                 aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <?php if ($author->youtube): ?>
                            <a href="<?php echo $author->youtube ?>" class="sc-link sc-youtube"><i class="fa fa-youtube"
                                                                                                 aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <a href="" class="sc-link sc-youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>

                        <?php if ($author->facebook): ?>
                            <a href="<?php echo $author->twitter ?>" class="sc-link sc-facebook"><i
                                    class="fa fa-facebook" aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <?php if ($author->twitter): ?>
                            <a href="<?php echo $author->twitter ?>" class="sc-link sc-twitter"><i class="fa fa-twitter"
                                                                                                   aria-hidden="true"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="item-des">
                    <?php echo macro()->more_block($author->desc); ?>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    };
    ?>
    <h4><?php echo lang("author") ?></h4>
    <hr/>
    <div class="gv">
        <div class="row">
            <?php foreach ($info->_author as $author): ?>
                <?php echo $_data_author($author); ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>