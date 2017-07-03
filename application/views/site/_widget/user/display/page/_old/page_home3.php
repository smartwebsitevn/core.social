<?php
//pr($list_feature);
//pr($widget);
?>
<!-- high school -->
<section class="high-school">
    <div class="container">
        <h2 class="cus-section-title section-title"><a href="#"><?php echo $widget->name; ?></a> </h2>
        <div class="slider-content">
            <div class="owl-carousel2">
                <?php view('tpl::_widget/user/display/list/list_home2', array('list' => $list)); ?>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>