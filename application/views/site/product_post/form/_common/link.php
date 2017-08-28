<div class="media  mt40 mb40">
    <a class="del-parent" data-parent=".media"><i class="pe-7s-close"></i></a>
    <input type="hidden" name="link" value="<?php echo $link ?>">
    <?php if( isset($tags['image'])): ?>
    <div class="media-left">
        <a href="<?php echo $link ?>">
            <img class="media-object" src="<?php echo $tags['image'] ?>" alt="..." >
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

