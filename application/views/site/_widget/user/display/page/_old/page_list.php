<?php
//$url = (isset($url_more) && $url_more)?$url_more:site_url('movie_list');
?>
<div class="bl-bai-giang-noi-bat">
    <div class="container">
        <h2><span><a href="#"><?php echo $widget->name; ?></a></span></h2>
           <?php view('tpl::_widget/lesson/display/list/list_default',array('list'=>$list));   ?>
        <?php if(isset($url_more) && $url_more): ?>
        <div class="views-more">
            <a href="<?php echo $url_more ?>">Xem thêm<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="clearfix"></div>