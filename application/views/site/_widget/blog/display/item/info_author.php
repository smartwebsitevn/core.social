<?php if (isset($info->_author) && $info->_author): ?>
    <?php
    $author = $info->_author;
    ?>
    <a class="author" href="<?php echo   $author->_url_view //$info->_url_author //?>">
        <span class="name"><?php echo $author->name ?></span>
        <img src="<?php echo $author->image->url_thumb ?>"
             alt="<?php echo $author->name ?>"></a>

<?php endif; ?>
