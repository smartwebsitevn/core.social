<?php if ($row->author_id || isset($row->_author) && $row->_author):
    /* if (!isset($row->_author))
         $author = mod('lesson_author')->get_info($row->author_id);
     else {
         $author = mod('lesson_author')->add_info($row->_author);
     }*/
   // pr($row->_author);
    $img='';
    if(isset($author->avatar->url))
        $img = $author->avatar->url;
        elseif(isset($author->image->url))
            $img = $author->image->url;

    ?>
    <span class="avatars-list">
        <?php foreach ($row->_author as $author):// pr($author);?>
            <span class="avatar">
         <img alt="<?php echo $author->name ?>"
             src="<?php echo $img// ?>">
          </span>
        <?php endforeach; ?>
    </span>
<?php endif; ?>
