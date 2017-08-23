<div class="media  mt40 mb40">
    <input type="hidden" name="link" value="<?php echo $link ?>">
    <?php if( isset($tags['image'])): ?>
    <div class="media-left">
        <a href="#">
            <img class="media-object" src="<?php echo $tags['image'] ?>" >
        </a>
    </div>
    <?php endif; ?>
    <div class="media-body">
        <h4 class="media-heading"><?php  echo isset($tags['title'])?$tags['title']:'' ?></h4>
        <?php if(isset($tags['description'])): ?>
            <small><?php echo  $tags['description']?></small>
        <?php endif; ?>

    </div>
</div>