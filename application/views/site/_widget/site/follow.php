<div class="heading">
    <div class="text text-social"></div>
</div>
<ul class="share text-center list-inline" >
    <li>
        <a href="" onmouseover="add_text_social('<?php echo $settings['address']?>, <?php echo lang('phone').': '.$settings['phone']?> và Email: <?php echo $settings['email']?>')"><i class="fa fa-phone"></i></a>
    </li>
    <?php if(isset($settings['googleplus']) && $settings['googleplus']){ ?>
    <li>
        <a href="<?php echo $settings['googleplus'] ?>" onmouseover="add_text_social('Follow Us On Google Plus')"><i class="fa fa-google-plus"></i></a>
    </li>
    <?php } ?>
    <?php if(isset($settings['twitter']) && $settings['twitter']){ ?>
    <li>
        <a href="<?php echo $settings['twitter'] ?>"  onmouseover="add_text_social('Follow Us On twitter')"><i class="fa fa-twitter"></i></a>
    </li>
    <?php } ?>
    <?php if(isset($settings['linkedin']) && $settings['linkedin']){ ?>
    <li>
        <a href="<?php echo $settings['linkedin'] ?>" onmouseover="add_text_social('Follow Us On Linkedin')"><i class="fa fa-linkedin"></i></a>
    </li>
    <?php } ?>
    <?php if(isset($settings['facebook']) && $settings['facebook']){ ?>
    <li>
        <a href="<?php echo $settings['facebook'] ?>"  onmouseover="add_text_social('Follow Us On Facebook')"><i class="fa fa-facebook"></i></a>
    </li>
    <?php } ?>
    <?php if(isset($settings['youtube']) && $settings['youtube']){ ?>
    <li>
        <a href="<?php echo $settings['youtube'] ?>"  onmouseover="add_text_social('Follow Us On YouTube')"><i class="fa fa-youtube"></i></a>
    </li>
    <?php } ?>
</ul>