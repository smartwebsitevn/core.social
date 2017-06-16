<?php //pr($info); ?>
<?php /* ?>
<div id="movie-data-info-<?php echo $info->id; ?>" style="display:none;visibility: hidden">
 <?php */ ?>

    <div class="block-product-main">
        <div class="container-fluid">
            <div class="item" style="background-image: url(<?php echo $info->banner->url ?>)">
                <a href="javascript:void(0)" onclick="$(this).closest('#movie-data-info').hide()" class="close-intro"><i class="fa fa-close"></i></a>
                <div class="box-content">
                    <div class="mb10">
                        <a class="product-item-name" href="<?php echo $info->_url_view ?>"><?php echo $info->name ?></a>
                    <?php if(isset($info->name_en) && $info->name_en): ?>
                        <a class="product-item-name-en" href="<?php echo $info->_url_view ?>"><?php echo $info->name_en ?></a>
                    <?php endif; ?>
                    </div>
                    <a href="<?php echo $info->_url_view ?>" class="product-play"></a>
                    <div class="product-item-detail">
                       <div class="product-hd">
                           <?php $this->load->view('site/movie/_common/attributes_special')?>
                       </div>
<?php if ($info->desc): ?>
                        <div class="product-des">
                            <p class="intro"><span style="color:#838383"><?php echo lang("content") ?>:</span> <?php echo $info->desc;//character_limiter_len($info->desc,200); ?></p>
                            <a href="<?php echo $info->_url_view ?>"><?php echo lang("view_more") ?></a>
                        </div>
<?php endif; ?>
                        <div class="product-info">
                            <?php if ($info->director): ?>
                                <p><span><?php echo lang('director') ?>: </span><?php echo $info->director; ?></p>
                            <?php endif; ?>
                            <?php if ($info->actor): ?>
                                <p><span><?php echo lang('actor') ?>: </span><?php echo $info->actor; ?></p>
                            <?php endif; ?>
                            <?php if ($info->_cat_names): ?>
                                <p><span><?php echo lang('cat') ?>: </span><?php echo $info->_cat_names; ?></p>
                            <?php endif; ?>
                            <?php if ($info->length): ?>
                                <p><span><?php echo lang('length') ?>
                                        : </span><?php echo $info->length  ? $info->length. ' '.lang('minute') : 'N/A'; ?> </p>
                            <?php endif; ?>
                            <?php if ($info->year): ?>
                                <p><span><?php echo lang('year') ?>: </span><?php echo $info->year; ?></p>
                            <?php endif; ?>
                        </div>
                        <?php  ?>
                        <?php if($info->demo): ?>
                        <p><a href="<?php echo $info->_url_view.'?demo=1&auto_play=1' ?>"  class="btn-trailer btn btn-default"><?php echo lang("watch_trailer") ?></a></p>
                        <?php endif; ?>
                        <?php //widget('movie')->action_favorite($info,'favorite_inline') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php /* ?>
</div>
<?php */ ?>