<?php if ($row->video):
    if (is_string($row->video_data)) {
        $link = json_decode($row->video_data);
    }
    $link = $link->link;
    ?>

    <iframe width="100%" height="300px"
            src="https://www.youtube.com/embed/<?php echo $link ?>?rel=0"
            frameborder="0" allowfullscreen></iframe>

<?php elseif (isset($row->link) && $row->link):
    $tags = json_decode($row->link_data,1);
    //pr($tags);
    ?>
    <div class="media">
        <?php if( isset($tags['image'])): ?>
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="<?php echo $tags['image'] ?>" >
                </a>
            </div>
        <?php endif; ?>
        <div class="media-body">
            <h5 class="media-heading"><?php  echo isset($tags['title'])?$tags['title']:'' ?></h5>
            <?php if(isset($tags['description'])): ?>
                <small><?php echo  $tags['description']?></small>
            <?php endif; ?>

        </div>
    </div>
<?php elseif (isset($row->images) && $row->images):
        $total_images = count($row->images);
        //pr($info->images)
        ?>
        <div class="images images-<?php echo $total_images > 5 ? 5 : $total_images; ?>">
            <?php $i = 0;
            foreach ($row->images as $img): $i++;// pr($row)
                if ($i > 5) break; ?>
                <div class="item item-<?php echo $i ?>">
                    <img src="<?php echo $img->_url; ?>">
                </div>
            <?php endforeach; ?>
        </div>

<?php endif; ?>