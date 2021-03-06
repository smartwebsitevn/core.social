<?php if ($row->video):
    if (is_string($row->video_data)) {
        $link = json_decode($row->video_data);
    }
    $link = $link->link;
    ?>

    <iframe src="https://www.youtube.com/embed/<?php echo $link ?>?rel=0"     frameborder="0" allowfullscreen></iframe>
<?php endif; ?>
<?php if (isset($row->link) && $row->link):
    $tags = json_decode($row->link_data, 1);
    //pr($tags);
    ?>
    <div class="media">
        <a href="<?php echo $row->link ?>" target="_blank">

            <?php if (isset($tags['image'])): ?>
                <div class="media-left">
                    <img class="media-object lazyload" data-src="<?php echo $tags['image'] ?>">
                </div>
            <?php endif; ?>
            <div class="media-body">
                <h5 class="media-heading"><?php echo isset($tags['title']) ? $tags['title'] : '' ?></h5>
                <?php if (isset($tags['description'])): ?>
                    <small><?php echo $tags['description'] ?></small>
                <?php endif; ?>
                <?php if(isset($tags['source_name'])): ?>
                    <br>
                    <small class="text-grey">Nguồn: <a href="<?php echo $tags['source_url'] ?>" target="_blank"><?php echo  $tags['source_name']?></a></small>
                <?php endif; ?>
            </div>
        </a>

    </div>
<?php endif; ?>

<?php if (isset($row->images) && $row->images):
    $total_images = count($row->images);
    //pr($row->images)
    ?>
    <?php if($total_images>1): ?>
    <div data-url="<?php echo $row->_url_view ?>" class="act-view-quick images images-<?php echo $total_images > 5 ? 5 : $total_images; ?>">
    <?php else: ?>
    <div class="images images-<?php echo $total_images > 5 ? 5 : $total_images; ?>">

    <?php endif ?>
        <?php $i = 0;
        foreach ($row->images as $img): $i++;// pr($row)
            if ($i > 5) break;

            $type = 'image';
            $youtube_id = '';
            if ($img->type == 'youtube') {
                $type = 'video';
                $youtube_id = $img->data;
            }
            $image=$img->_url;

            /*if($total_images==1)
                $image=$img->_url;
            elseif($total_images==3 && $i==0){
                $image=$img->_url;
            }*/
            ?>
            <div class="item item-<?php echo $i ?>">
                <img class="lazyload" data-src="<?php echo  $image?>">
                <?php if ($youtube_id): ?>
                    <div class="item-video">
                        <div
                            class="item-video-icon" <?php echo $youtube_id ? ' data-youtube="' . $youtube_id . '"' : '' ?> ></div>
                            <?php if($total_images==1): ?>
                            <div class="item-video-player"></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if ($total_images > 5): ?>
            <div class="over">+<?php echo ($total_images - 5)?>...</div>
        <?php endif; ?>
    </div>

<?php endif; ?>