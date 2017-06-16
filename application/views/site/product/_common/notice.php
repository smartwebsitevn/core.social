<div class="alert alert-danger p20 text-center">
    <?php if ($is_login): ?>
        <span class="f20">
            <?php echo lang("notice_cannot_view_lesson_need_buy") ?>

            <?php if($lesson->price_option == 1){ ?>
    <a href="<?php echo site_url('checkout') ?>?lesson_id=<?php echo $lesson->id ?>" class="btn btn-info  btn-lg btn-block mt10 mb10"">
        <i class="fa fa-shopping-cart"></i> <?php echo lang("take_this_lesson") ?></a>
    <?php } else if($lesson->price_option == 2){ ?>
    <a href="<?php echo site_url('checkout') ?>?product_id=<?php echo $info->id ?>" class="btn btn-info  btn-lg btn-block mt10 mb10"">
        <i class="fa fa-shopping-cart"></i> <?php echo lang("take_this_product") ?></a>
    <?php } ?>
            <a href="<?php echo $info->_url_view ?>" class="btn btn-info btn-lg btn-block mt10 mb10">
                <i class="fa fa-info"></i> <?php echo lang('product_info') ?></a>

    </span>
    <?php else: ?>

        <span class="f20">
               <?php echo lang("notice_need_login") ?>
                <a href="<?php echo site_url("login") ?>" class="btn btn-info btn-lg  btn-lg btn-block mt10 mb10"> <?php echo lang('button_login') ?></a>
                <a href="<?php echo site_url("register") ?>" class="btn btn-info btn-lg  btn-lg btn-block mt10 mb10"><?php echo lang('button_register') ?></a>

    </span>
    <?php endif; ?>

</div>