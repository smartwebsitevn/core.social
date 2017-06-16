<?php
$asset = public_url();
$asset_theme = $asset . '/site';
//pr($movie);
$digital_types =mod('movie')->config('digital_types');
?>
<?php if($movie->digital_type): ?>
    <img class="mr5" src="<?php echo $asset_theme ?>/images/digital_<?php echo $digital_types[$movie->digital_type]?>.png">
<?php endif; ?>
<?php if(in_array($movie->_quality,array('hd_720', 'hd_1080'))): ?>
    <img  class="mr5" src="<?php echo $asset_theme ?>/images/quality_<?php echo $movie->_quality ?>.png">
<?php  endif; ?>
<?php if($movie->has_interpret): ?>
    <img class="mr5" title="Phim có thuyết minh" src="<?php echo $asset_theme ?>/images/interpret.png">
<?php endif; ?>
<?php if($movie->is_18): ?>
    <img class="mr5" title="Phim có dành cho độ tuổi 18+"  src="<?php echo $asset_theme ?>/images/18plus.png">
<?php endif; ?>

 