<?php
$image1 = file_get_image_from_name($widget->setting['image1']);
$image2 = file_get_image_from_name($widget->setting['image2']);
$image3 = file_get_image_from_name($widget->setting['image3']);
//$image4 = file_get_image_from_name($widget->setting['image4']);
//pr($widget);
?>
<section class="first-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h4><?php echo $widget->setting['partner_name'] ?></h4>
                <?php foreach ($widget->setting['partner_images'] as $val): ?>
                        <img src="<?php echo upload_url($val); ?>" alt="<?php echo $widget->name; ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<section class="second-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
               <?php echo $widget->setting['header'] ?>
                <div class="row">
                    <div class="col-sm-4">
                        <a href="<?php echo $widget->setting['link1'] ?>" target="_blank"><img src="<?php echo $image1->url ?>"
                                                                               alt="<?php echo $widget->setting['name1'] ?>"></a>
                        <h4><?php echo $widget->setting['name1'] ?></h4>
                        <span><?php echo $widget->setting['content1'] ?></span>
                    </div>
                    <div class="col-sm-4">
                        <a href="<?php echo $widget->setting['link2'] ?>" target="_blank"><img src="<?php echo $image2->url ?>"
                                                                                               alt="<?php echo $widget->setting['name2'] ?>"></a>
                        <h4><?php echo $widget->setting['name2'] ?></h4>
                        <span><?php echo $widget->setting['content2'] ?></span>
                    </div>
                    <div class="col-sm-4">
                        <a href="<?php echo $widget->setting['link3'] ?>" target="_blank"><img src="<?php echo $image3->url ?>"
                                                                                               alt="<?php echo $widget->setting['name3'] ?>"></a>
                        <h4><?php echo $widget->setting['name3'] ?></h4>
                        <span><?php echo $widget->setting['content3'] ?></span>
                    </div>
                </div>
                <?php echo $widget->setting['footer'] ?>
            </div>
        </div>
    </div>
</section>



