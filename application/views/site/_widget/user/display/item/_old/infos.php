<div class="views-row col-xs-12 col-sm-4 col-lg-4">
    <div class="box-wrap">
        <div class="images">
            <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>"><img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>" /></a>
        </div>
        <div class="info">
            <div class="title">
                <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>
            </div>
            <div class="name-gv" title="<?php echo lang("author") ?>"><i class="fa fa-mortar-board"></i> <?php echo $row->_author_name ?></div>
            <div class="views" title="<?php echo lang("count_view") ?>"><?php echo number_format($row->count_view) ?></div>
            <div class="comment" title="<?php echo lang("count_comment") ?>"><?php echo number_format($row->comment_count) ?></div>
        </div>
    </div>
</div>
<?php /* ?>
<div class="film-item">
    <div class="film-item-info">
        <div class="film-item-photo">
            <a class="film-item-image showTip movie-data-tooltip-<?php echo $row->id;?>" href="<?php echo $row->_url_view; ?>">
                <img  src="<?php //echo $row->image->url_thumb; ?>">
            </a>
            <?php //view('tpl::_widget/lesson/display/item/label',array('movie'=>$row)); ?>
        </div>
        <a href="<?php echo $row->_url_view; ?>" class="film-item-name"
           title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>
        <?php
        $views = $row->view_total;
        $views =  number_format($views);
        ?>
        <span class="film-item-review">Lượt xem: <?php echo $views; ?> </span>
        <?php ?>
    </div>
    <?php //view('tpl::_widget/lesson/display/item/info_tooltip',array('movie'=>$row)); ?>

</div>
<?php //view('tpl::_widget/movie/display/item/info_inline',array('movie'=>$row)); ?>

<?php */ ?>

