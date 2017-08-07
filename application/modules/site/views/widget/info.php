<!-- Intro -->
<?php
$image = file_get_image_from_name($widget->setting['image']);
?>
<section id="intro">
    <a href="<?php echo site_url() ?>" class="logo">
        <img src="<?php echo $image->url//widget("site")->setting_image() ?>" alt="logo"/>
    </a>
    <header>
        <h2><?php echo  $widget->setting["title"]?></h2>
    </header>


<?php $settings = setting_get_group('config'); ?>
<ul class="icons">
    <?php if(isset($settings['googleplus']) && $settings['googleplus']){ ?>
        <li>
            <a href="<?php echo $settings['googleplus'] ?>"><i class="fa fa-google-plus"></i></a>
        </li>
    <?php } ?>
    <?php if(isset($settings['facebook']) && $settings['facebook']){ ?>
        <li>
            <a href="<?php echo $settings['facebook'] ?>" ><i class="fa fa-facebook"></i></a>
        </li>
    <?php } ?>
    <?php if(isset($settings['youtube']) && $settings['youtube']){ ?>
        <li>
            <a href="<?php echo $settings['youtube'] ?>" ><i class="fa fa-youtube"></i></a>
        </li>
    <?php } ?>
    <?php if(isset($settings['email']) && $settings['email']){ ?>
        <li>
            <a href="<?php echo $settings['email'] ?>" ><i class="fa fa-envelope"></i></a>
        </li>
    <?php } ?>
</ul>
</section>
<!-- About -->
<section class="blurb">
    <?php echo  $widget->setting["intro"]?>
    <?php if($widget->setting["link"]): ?>
        <ul class="actions">
            <li><a href="<?php echo  $widget->setting["link"]?>" class="button">Read More</a></li>
        </ul>
    <?php endif; ?>
</section>