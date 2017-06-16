<div class="media">
    <div class="media-body">
        <h4 class="media-heading"><?php echo $info->name; ?></h4>
        <div class="media-content">
            <?php echo $info->brief; ?><br>
        </div>
    </div>
    <div class="media-right">
        <a href="<?php echo $info->_url_view; ?>">
            <img class="media-object" src="<?php echo thumb_img($info->image)//$info->image->url_thumb; ?>"
                 alt="<?php echo $info->name; ?>">
        </a>
    </div>
</div>
<div class="mt20">
<?php echo html_entity_decode($info->description); ?>
</div>
