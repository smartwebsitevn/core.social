<?php // $this->widget->movie->comment($movie)
$_comment = function ($row) use ($type) {
    ob_start();
    $name = $row->user ? $row->user_name : 'Dungmori';
    $img = (isset($row->user->avatar) && $row->user->avatar) ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png');
    ?>
    <div class="row mt10">
        <div class="col-md-1">
            <?php $name = $row->user ? $row->user_name : 'Dungmori';
            $img = (isset($row->user->avatar) && $row->user->avatar) ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png');
            ?>
            <a class="pull-left" href="#">
                <img alt=""
                     src="<?php echo $img ?>"
                     class="avatar">
            </a>


        </div>
        <div class="col-md-11">
            <span class="name"><b class="red"><?php echo $name . ' </b>- ' . $row->_created_time ?></span>

            <p class="comment-content"><?php echo $row->content ?></p>


            <div class="comment-btn">
                <a data-toggle="collapse" href="#reply_<?php echo $row->id ?>" aria-expanded="false"
                   aria-controls="reply_<?php echo $row->id ?>"
                   class="reply-btn">Trả lời (<?php echo count($row->subs) ?>) </a>
            </div>
            <div class="collapse  mt20  " id="reply_<?php echo $row->id ?>">
                <form  class="form_action" accept-charset="UTF-8"   action="<?php echo site_url('comment/reply/'.$row->id) ?>" method="POST">

                    <!--<img src="<?php /*//echo !$user->avatar?$user->avatar->url_thumb:public_url('site/layout/img/default-avatar.png')*/ ?>" class="media-object user-avatar pull-left">-->

                    <div class="form-group text-right">
                                      <textarea style="width: 70%;height: 60px;float:left;margin-right:20px"
                                                name="content"
                                                placeholder="<?php echo lang("comment") ?>..."
                                                class="form-control"></textarea>
                        <input type="submit" value="Post" class="btn btn-primary btn-xs pull-left">


                        <div class="clear"></div>
                        <div name="content_error" class="error "></div>
                        <div name="user_error" class="error "></div>
                    </div>
            </div>
            </form>
            <?php if (isset($row->subs) && $row->subs): ?>
                <ul class="list-unstyled">
                    <?php foreach ($row->subs as $sub): //pr($sub);?>
                        <li>
                            <?php echo $_comment($sub) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>


    </div>

    <?php
    return ob_get_clean();
}
?>
