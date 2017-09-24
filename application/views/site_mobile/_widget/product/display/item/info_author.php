<?php if ($row->author_id || isset($row->_author) && $row->_author):
    $author = $row->_author; //pr($author);
    ?>
    <div class="item-photo">
        <?php echo view('tpl::_widget/user/display/item/info_avatar', array('row' => $author)); ?>

    </div>
    <div class="item-info">
        <span class="name">
         <a href="<?php echo $author->_url_view; ?>">
             <?php echo $author->name ?>

         </a>
       </span>

        <div class="item-meta">
            <span class="profession"><?php echo $author->profession ?></span>
            <?php if (isset($author->_working_city_name) && $author->_working_city_name): ?>
                <span class="place"> <i   class="pe-7s-map-marker"></i> <?php echo $author->_working_city_name ?>
                                    </span>
            <?php endif; ?>
        </div>
    </div>
    <span class="<?php echo isset($item_time_manager)?'item-time-manager':'item-time' ?>"><?php echo $row->_created_carbon->diffForHumans(); ?>  </span>
<?php endif; ?>