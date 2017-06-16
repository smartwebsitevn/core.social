<?php
//pr($list_feature);
?>
<!-- College, University -->
<section class="college_university">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="ask-left">
                    <a href="<?php echo site_url("my-test-result") ?>">
                        <img src="<?php echo public_url('site/theme') ?>/images/ask-left1.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-md-push-4 col-sm-6">
                <div class="ask-right">
                    <a href="<?php echo site_url("question_answer") ?>">
                        <img src="<?php echo public_url('site/theme') ?>/images/ask-right1.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-md-pull-4 col-sm-12">
                <h2 class="section-title"><a href="#"><?php echo $widget->name; ?></a></h2>
            </div>
        </div>
        <div class="slider-content">
            <div class="owl-carousel1">
                <?php view('tpl::_widget/product/display/list/list_home2', array('list' => $list)); ?>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>