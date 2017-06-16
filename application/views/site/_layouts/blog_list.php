<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => "page_blog"]); ?>
</head>
<body class="page-blog">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="container">


            <div class="row pt10 pb20">
                <?php echo html_entity_decode(mod('blog')->setting('blog_intro')); ?>
            </div>
            <div class="row">
            <?php echo widget('blog')->cat() ?>
            </div>

            <div class="row">
                <div class="col-md-8 main-content">
                    <?php //echo $content_top; ?>
                    <?php echo $content; ?>
                    <?php //echo $content_bottom; ?>
                </div>
                <div class="col-md-4 sidebar right-sidebar">
                    <?php echo widget('blog')->filter(isset($filter) ? $filter : array(), "sidebar") ?>
                    <?php /* ?>
                    <div class="block block-dang-bai-moi">
                        <div class="block-title">
                        </div>
                        <div class="block-content">
                            <a href="<?php //echo $recruit_settings['post_blog_url_help'] ?>">Gửi bài viết mới<?php //echo lang("post_blog")?></a>
                            <a href="<?php //echo $recruit_settings['post_blog_url_help'] ?>" class="mail">shop.vn@gmail.com<?php //echo $email_config ?></a>
                        </div>
                    </div>
                    <?php */ ?>

                    <div class="block block-follow-us">
                        <div class="block-title">
                            Follow us<?php //echo lang("follow_us")?>
                        </div>
                        <div class="block-content">
                            <ul class="social list-inline">
                                <?php 	$settings = setting_get_group('config'); ?>
                                <?php foreach(array('googleplus','twitter','linkedin','facebook','youtube') as $row){
                                    if(isset($settings[$row]) && $settings[$row]){?>
                                        <li>
                                            <a  href="<?php echo $settings[$row] ?>"><i class="fa fa-<?php echo $row=='googleplus' ? 'google-plus' : $row?>"></i></a>
                                        </li>

                                    <?php }} ?>
                            </ul>

                            <form  class="form-subcribe subcribe clearfix form_action" action="<?php echo site_url('home/email_register'); ?>" method="POST">
                                <input type="text" placeholder="Email address<?php //echo lang("email_address")?>" class="form-control" name="email">
                                <button class="btn btn-default" type="submit"><?php echo lang("subcribe")?></button>
                                <div class="error text-danger clear" name="email_error"></div>
                            </form>
                        </div>
                    </div>

                    <div class="block block-post-noi-bat">
                        <div class="block-title"> Bài viết nổi bật    </div>
                        <div class="block-content">
                            <?php echo widget('blog')->feature([],'sidebar') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

