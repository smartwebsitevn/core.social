<?php $url = (isset($url_more) && $url_more) ? $url_more : site_url('product_list');
?>
<?php /*if (isset($info->_cat) && $info->_cat): ?>
    <p>
        <span><?php echo lang('cat') ?>: </span>
        <?php  $d=0;foreach ($info->_cat as $it): ?>
            <?php if($d++ >0) echo ',' ?>
            <a href="<?php echo $url . '?cat=' . $it->id; ?>" title="<?php echo $it->name; ?>" target="_blank"><?php echo $it->name; ?></a>
        <?php endforeach; ?>
    </p>
<?php endif; */?>
<?php if (isset($info->_cat) && $info->_cat): ?>
    <p>
        <span>Phân loại<?php //echo lang('category') ?>: </span>
            <a class="item-tag" href="<?php echo $url . '?cat_id=' . $info->_cat->id; ?>" title="<?php echo $info->_cat->name; ?>" target="_blank"><?php echo $info->_cat->name; ?></a>
    </p>
<?php endif; ?>
<?php  if (isset($info->_manufacture) && $info->_manufacture): ?>
    <p>
        <span>Hãng sản xuất<?php //echo lang('category') ?>: </span>
        <a class="item-tag" href="<?php echo $url . '?manufacture_id=' . $info->_manufacture->id; ?>" title="<?php echo $info->_manufacture->name; ?>" target="_blank"><?php echo $info->_manufacture->name; ?></a>
    </p>
<?php endif; ?>
<?php if (isset($info->_country) && $info->_country): //pr($info);?>
    <p><span>Xuất xứ<?php //echo lang('country') ?>: </span>
            <a  class="item-tag" href="<?php echo $url . '?country_id=' . $info->country_id; ?>" title="<?php echo $info->_country_name; ?>" target="_blank"><?php echo $info->_country_name; ?></a>
    </p>
<?php endif; ?>
<?php if (isset($info->_warranty) && $info->_warranty): ?>
    <p>
        <span>Bảo hành<?php //echo lang('category') ?>: </span>
        <a class="item-tag" href="<?php echo $url . '?warranty_id=' . $info->_warranty->id; ?>" title="<?php echo $info->_warranty->name; ?>" target="_blank"><?php echo $info->_warranty->name; ?></a>
    </p>
<?php endif; ?>
<?php  if (isset($info->_tag) && $info->_tag): ?>
    <p>
        <span><?php echo lang('Tag') ?>: </span>
        <?php $d=0; foreach ($info->_tag as $it): ?>
            <?php if(!$it->seo_url) continue; ?>
            <?php if($d++ >0) echo ',' ?>
            <a  class="item-tag" href="<?php echo site_url('tag/'.$it->seo_url) ?>" title="<?php echo $it->name; ?>"  target="_blank" ><?php echo $it->name ?></a>
        <?php endforeach; ?>

    </p>
<?php endif; ?>
<?php  if (isset($info->is_alway_in_stock) && !$info->is_alway_in_stock): ?>
    <p>
        <span>Tình trạng<?php //echo lang('Tag') ?>: </span>
        <b  >
            <?php  if($info->quantity <=0):?>
                Hết hàng
            <?php else: ?>
                Còn <?php echo $info->quantity  ?> tin bài
            <?php  endif; ?>

        </b>
    </p>
<?php endif; ?>
<?php /* if (isset($info->quantity)): ?>
    <p>
        <span>Tình trạng<?php //echo lang('Tag') ?>: </span>
            <b  >
            <?php  if($info->quantity ==0):?>
                Hết hàng
                <?php else: ?>
                Còn <?php echo $info->quantity  ?> tin bài
            <?php endif; ?>

          </b>
    </p>
<?php endif; */?>