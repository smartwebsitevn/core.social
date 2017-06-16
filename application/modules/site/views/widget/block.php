<?php
$block = $widget->setting;
$img = file_get_image_from_name($block['image']);
$img = $img->url;
$link = handle_content($block['link'], 'output');
$title = handle_content($block['title'], 'output');
$content = handle_content($block['content'], 'output');
$container = handle_content($block['container'], 'output');
?>
<div class="block_wraper <?php echo $container ?>">

    <?php if ($block['image_align'] == "left"): ?>
        <div class="row">
            <div class="col-md-6 ">
                <img src="<?php echo $img ?>" style="width: 100%">
            </div>
            <div class="col-md-6 ">
                <div class="block_title mt10 fontB">
                    <?php echo $title ?>
                </div>
                <div class="block_content mt10">
                    <?php echo $content ?>

                </div>
            </div>
        </div>
    <?php elseif ($block['image_align'] == "right"): ?>
        <div class="row">
            <div class="col-md-6 ">
                <div class="block_title mt10 fontB">
                    <?php echo $title ?>
                </div>
                <div class="block_content mt10">
                    <?php echo $content ?>

                </div>
            </div>
            <div class="col-md-6">
                <img src="<?php echo $img ?>" style="width: 100%">
            </div>
        </div>

    <?php elseif ($block['image_align'] == "background"): ?>
        <div class="row" style=" background-image: url('<?php echo $img ?>'); background-repeat: no-repeat">
            <div class="col-md-6 ">
                <div class="block_title mt10 fontB">
                    <?php echo $title ?>
                </div>
                <div class="block_content mt10">
                    <?php echo $content ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>
<div class="clearfix"></div>
