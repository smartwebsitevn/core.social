<?php //pr($row); ?>
<?php /* ?>
<div id="movie-data-info-<?php echo $row->id; ?>" style="display:none;visibility: hidden">
 <?php */ ?>

    <div class="block-product-main">
        <div class="container-fluid">
            <div class="item" style="background-image: url(<?php echo $row->banner->url ?>)">
                <a href="javascript:void(0)" onclick="$(this).closest('#movie-data-info').hide()" class="close-intro"><i class="fa fa-close"></i></a>
                <div class="box-content">
                    <div class="mb10">
                        <a class="product-item-name" href="<?php echo $row->_url_view ?>"><?php echo $row->name ?></a>
                    <?php if(isset($row->name_en) && $row->name_en): ?>
                        <a class="product-item-name-en" href="<?php echo $row->_url_view ?>"><?php echo $row->name_en ?></a>
                    <?php endif; ?>
                    </div>
                    <a href="<?php echo $row->_url_view ?>" class="product-play"></a>
                    <div class="product-item-detail">
                       <div class="product-hd">
                           <?php $this->load->view('site/movie/_common/attributes_special')?>
                       </div>
<?php if ($row->desc): ?>
                        <div class="product-des">
                            <p class="intro"><span style="color:#838383"><?php echo lang("content") ?>:</span> <?php echo $row->desc;//character_limiter_len($row->desc,200); ?></p>
                            <a href="<?php echo $row->_url_view ?>"><?php echo lang("view_more") ?></a>
                        </div>
<?php endif; ?>
                        <div class="product-info">
                            <?php if ($row->director): ?>
                                <p><span><?php echo lang('director') ?>: </span><?php echo $row->director; ?></p>
                            <?php endif; ?>
                            <?php if ($row->actor): ?>
                                <p><span><?php echo lang('actor') ?>: </span><?php echo $row->actor; ?></p>
                            <?php endif; ?>
                            <?php if ($row->_cat_names): ?>
                                <p><span><?php echo lang('cat') ?>: </span><?php echo $row->_cat_names; ?></p>
                            <?php endif; ?>
                            <?php if ($row->length): ?>
                                <p><span><?php echo lang('length') ?>
                                        : </span><?php echo $row->length  ? $row->length. ' '.lang('minute') : 'N/A'; ?> </p>
                            <?php endif; ?>
                            <?php if ($row->year): ?>
                                <p><span><?php echo lang('year') ?>: </span><?php echo $row->year; ?></p>
                            <?php endif; ?>
                        </div>
                        <?php  ?>
                        <?php if($row->demo): ?>
                        <p><a href="<?php echo $row->_url_view.'?demo=1&auto_play=1' ?>"  class="btn-trailer btn btn-default"><?php echo lang("watch_trailer") ?></a></p>
                        <?php endif; ?>
                        <?php //widget('movie')->action_favorite($row,'favorite_inline') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php /* ?>
</div>
<?php */ ?>