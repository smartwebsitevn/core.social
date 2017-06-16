<?php if ($info->author_id || isset($info->_author) && $info->_author):
    /* if (!isset($info->_author))
         $author = mod('lesson_author')->get_info($info->author_id);
     else {
         $author = mod('lesson_author')->add_info($info->_author);
     }*/

   // pr($info->author);

    ?>
    <?php foreach ($info->_author as $author):// pr($author);
    $img = '';
    if (isset($author->avatar->url))
        $img = $author->avatar->url;
    elseif (isset($author->image->url))
        $img = $author->image->url;
    ?>
    <div class="item-meta">
        <p class="avatar">
           <a href="<?php echo $author->_url_view  ?>"> <img src="<?php echo $img ?>" alt="<?php echo $author->name ?>"></a>
        </p>

    </div>
<?php endforeach; ?>
<?php endif; ?>
