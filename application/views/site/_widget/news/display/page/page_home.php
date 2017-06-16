<?php
//$url = (isset($url_more) && $url_more)?$url_more:site_url('movie_list');
?>
<div class="clearfix"></div>
<section class="work-ad slide-blog mb0">
    <div class="container">
        <div class="heading"><?php echo $widget->name ?></div>
        <div class="owl-carousel">
            <?php foreach($list as $row){?>
                <div class="item-category-blog">
                    <div class="img">
                        <a href="<?php echo $row->_url_view?>" title="<?php echo $row->title ?>">
                            <img class="img-responsive" src="<?php echo  $row->image->url ?>">
                        </a>
                    </div>
                    <div class="caption">
                        <a href="<?php echo $row->_url_view ?>" class="name" title="<?php echo $row->title ?>"><?php echo $row->title ?></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<div class="clearfix"></div>