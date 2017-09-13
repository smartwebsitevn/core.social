<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php widget('site')->head(["css" => "page_blog"]); ?>
</head>
<body class="page-blog">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="container">

            <div class="row">
                <div class="page-title-wrapper">
                        <h1 class="title-page"><?php echo $info->name ?></h1>

                </div>
                 <div class="clearfix"></div>

                <div class="col-md-8 main-content">
                    <?php //echo $content_top; ?>
                    <?php echo $content; ?>
                    <?php //echo $content_bottom; ?>
                </div>
                <div class="col-md-4 sidebar right-sidebar mt30">
                    <?php //echo widget('blog')->filter(isset($filter) ? $filter : array(), "sidebar") ?>
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
                            <form class="form-subcribe subcribe clearfix form_action" action="<?php echo site_url('blog/subcribe') ?>" method="post">
                                <input type="text" placeholder="Email address<?php //echo lang("email_address")?>" class="form-control" name="email">
                                <button class="btn btn-default" type="submit"><?php echo lang("subcribe")?></button>
                                <div class="error text-danger clear" name="email_error"></div>
                            </form>
                        </div>
                    </div>
                    <div class="block block-post-noi-bat">
                        <div class="block-title"> Chủ đề    </div>
                        <div class="block-content">
                            <?php echo widget('blog')->cat([],'sidebar') ?>
                        </div>
                    </div>
                    <div class="block block-post-noi-bat">
                        <div class="block-title"> Bài viết cùng chủ đề    </div>
                        <div class="block-content">
                            <?php echo widget('blog')->same_cat($info->cat_id,[],'sidebar') ?>
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

