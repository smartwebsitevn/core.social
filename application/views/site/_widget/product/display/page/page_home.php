<?php
//pr($widget);
?>

<div class="block-khoahoc">
    <div class="container">
        <div class="block-title heading-opt2">
            <strong class="title"><?php echo $widget->name ?></strong>
        </div>
        <div class="block-content">
            <div class="owl-carousel carousel-khoahoc">
                <?php view('tpl::_widget/product/display/list/list_home', array('list' => $list)); ?>
            </div>
        </div>
    </div>
</div>