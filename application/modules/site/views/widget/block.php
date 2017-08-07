<?php
$block = $widget->setting;

$img = file_get_image_from_name($block['image']);
$img = $img->url;
$container_wraper = $block['container_wraper'];

$bg=false;
$style=[];
if($block['image_align'] == "background"){
    $style[]='background-image: url('.$img.'); background-repeat: no-repeat;background-position: center center;
background-size: cover;';
    $bg=true;
}

if($block['container_color'])
    $style[]='background-color: '.trim($block['container_color']);
$_data_content=function() use($block){
    $link = handle_content($block['link'], 'output');
    $title = handle_content($block['title'], 'output');
    $content = handle_content($block['content'], 'output');
  ob_start()?>
    <div class="block_title ">
        <?php echo $title ?>
    </div>
    <div class="block_content ">
        <?php echo $content ?>
        <?php if($block['link']) ?>
        <br>
        <a href="<?php echo $link ?>" target="_blank" class="btn btn-default mt40">Xem thêm</a>
    </div>
<?php return ob_get_clean();
}
?>
<div class="block_sysem <?php echo $container_wraper ?>"  style="<?php echo $style?implode(";",$style):'' ?>"  >

    <?php if ($block['image_align'] == "left"): ?>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12  ">
                <img src="<?php echo $img ?>" style="width: 100%">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12  ">
               <?php echo $_data_content(); ?>
            </div>
        </div>
    <?php elseif ($block['image_align'] == "right"): ?>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12  ">
                <?php echo $_data_content(); ?>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 ">
                <img src="<?php echo $img ?>" style="width: 100%">
            </div>
        </div>

    <?php elseif ($bg): ?>
        <div class="row" >
            <?php echo $_data_content(); ?>
        </div>

    <?php endif; ?>
</div>
<div class="clearfix"></div>
