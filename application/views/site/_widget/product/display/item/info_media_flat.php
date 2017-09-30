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
    //pr($row->images)
    ?>
    <div class="images ">
        <?php $i = 0;
        foreach ($row->images as $img): $i++;// pr($row)
            $type = 'image';
            $youtube_id = '';
            if ($img->type == 'youtube') {
                $type = 'video';
                $youtube_id = $img->data;
            }
            ?>
            <div class="item item-<?php echo $i ?>">
                <img class="lazyload" data-src="<?php echo $img->_url; ?>">
                <?php if ($youtube_id): ?>
                    <div class="item-video">
                        <div
                            class="item-video-icon" <?php echo $youtube_id ? ' data-youtube="' . $youtube_id . '"' : '' ?> ></div>
                            <div class="item-video-player"></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>