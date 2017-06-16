<?php
//$url = (isset($url_more) && $url_more)?$url_more:site_url('movie_list');
?>
<!-- News -->
<section class="slide-blog mb0">
    <div class="container">
        <div class="heading"><?php echo $widget->name ?></div>
        <div class="owl-blog">
            <?php foreach($categoryblog as $row){?>
                <div class="item-category-blog">
                    <div class="img">
                        <a href="<?php echo $row->_url_view?>" title="<?php echo $row->name ?>">
                            <img class="img-responsive" src="<?php echo $row->image_name ? $row->image->url : ''?>">
                        </a>
                    </div>
                    <div class="caption">
                        <a href="<?php echo $row->_url_view ?>" class="name" title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
                        <span><?php echo $row->total ?> blog</span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>