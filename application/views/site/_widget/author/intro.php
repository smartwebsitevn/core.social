<div class="gioi-thieu-gv">
    <span class="heading"><?php echo lang('author_introduce') ?></span>
    <div class="author-gv">
        <a href="<?php echo $author->_url_view ?>" class="img-left"><img src="<?php echo $author->image->url ?>" alt="" /></a>

    </div>
    <div class="author-right">
        <p class="name"><?php echo $author->name ?></p>
        <span class="cv"><?php echo $author->information ?></span>
    </div>
    <div class="gv-content">
        <?php echo $author->description ?>
    </div>
</div>