<?php if (isset($info->video_data) && $info->video_data): ?>
    <div class="block-info p0">
        <?php echo widget("media")->player($info->video_data, ['image_url' => $info->banner->url], 'player') ?>
    </div>
<?php endif; ?>